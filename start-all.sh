#!/usr/bin/env bash
set -e

DIR="$(cd "$(dirname "$0")" && pwd)"
BOTDIR="$DIR/telegram-bridge"
PIDFILE="$DIR/.all-pids"
STOPSCRIPT="$DIR/stop-all.sh"

echo "============================================"
echo " Starting INFAQABIM - All Services"
echo "============================================"

pkill -F "$PIDFILE" 2>/dev/null || true
rm -f "$PIDFILE"
sleep 1

# 1. Laravel Artisan Server (port 8000)
echo "[1/5] Starting Laravel Artisan server..."
nohup php artisan serve --port 8000 > /tmp/infaqabim-artisan.log 2>&1 &
ARTISAN_PID=$!
echo "artisan:$ARTISAN_PID" >> "$PIDFILE"

# 2. Vite Dev Server (port 5173)
echo "[2/5] Starting Vite dev server..."
nohup npm run dev > /tmp/infaqabim-vite.log 2>&1 &
VITE_PID=$!
echo "vite:$VITE_PID" >> "$PIDFILE"

# 3. OpenCode Server (port 4096)
echo "[3/5] Starting OpenCode server..."
OPENCODE_SERVER_PASSWORD=infaq123 nohup opencode serve --port 4096 --log-level ERROR > /tmp/infaqabim-opencode.log 2>&1 &
OPENCODE_PID=$!
echo "opencode:$OPENCODE_PID" >> "$PIDFILE"
sleep 5

if ! kill -0 $OPENCODE_PID 2>/dev/null; then
  echo "OpenCode server failed. Check /tmp/infaqabim-opencode.log"
  cat /tmp/infaqabim-opencode.log
fi

# 4. Telegram Bot
echo "[4/5] Starting Telegram bot..."
cd "$BOTDIR"
nohup node src/index.js > /tmp/infaqabim-bot.log 2>&1 &
BOT_PID=$!
echo "bot:$BOT_PID" >> "$PIDFILE"
cd "$DIR"

# 5. Cloudflare Tunnel untuk Laravel
echo "[5/5] Starting Cloudflare tunnel for Laravel..."
BIN="$BOTDIR/bin/cloudflared"
TMPLOG=$(mktemp)
nohup $BIN tunnel --url http://localhost:8000 > "$TMPLOG" 2>&1 &
TUNNEL_PID=$!
echo "tunnel:$TUNNEL_PID" >> "$PIDFILE"

# Tunggu URL tunnel
for i in $(seq 1 15); do
  URL=$(grep -o 'https://[a-z0-9-]*\.trycloudflare\.com' "$TMPLOG" 2>/dev/null | tail -1)
  [ -n "$URL" ] && break
  sleep 1
done

echo ""
echo "============================================"
echo " ALL SERVICES RUNNING"
echo "============================================"
echo " PIDs saved in: $PIDFILE"
echo ""
echo " Local URLs:"
echo "   Laravel    : http://localhost:8000"
echo "   Vite       : http://localhost:5173"
echo "   OpenCode   : http://localhost:4096"
echo "   Telegram   : @CyberocketAssistanceBot"
echo ""
if [ -n "$URL" ]; then
  echo " From Phone:"
  echo "   $URL  <- Laravel website"
  echo ""
  echo " Untuk OpenCode web UI dari phone,"
  echo " buka terminal lain & jalan:"
  echo "   $BIN tunnel --url http://localhost:4096"
fi
echo "============================================"
echo " To stop: bash $STOPSCRIPT"
echo "============================================"

caffeinate -i -w $$ > /dev/null 2>&1 &

trap "echo 'Stopping...'; pkill -F "$PIDFILE" 2>/dev/null; rm -f "$PIDFILE" $TMPLOG; echo 'Done.'; exit 0" SIGINT SIGTERM

wait
