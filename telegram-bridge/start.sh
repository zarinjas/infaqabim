#!/usr/bin/env bash
set -e

DIR="$(cd "$(dirname "$0")" && pwd)"
PIDFILE="$DIR/.pids"
cd "$DIR/.."

pkill -F "$PIDFILE" 2>/dev/null || true
rm -f "$PIDFILE"
sleep 1

echo "Starting opencode server..."
OPENCODE_SERVER_PASSWORD=infaq123 nohup opencode serve --port 4096 --log-level ERROR > /tmp/infaqabim-opencode.log 2>&1 &
OPENCODE_PID=$!
echo "opencode:$OPENCODE_PID" >> "$PIDFILE"
sleep 5

if ! kill -0 $OPENCODE_PID 2>/dev/null; then
  echo "OpenCode server failed. Check /tmp/infaqabim-opencode.log"
  cat /tmp/infaqabim-opencode.log
  exit 1
fi

echo "Starting Telegram bot..."
cd "$DIR"
nohup node src/index.js > /tmp/infaqabim-bot.log 2>&1 &
BOT_PID=$!
echo "bot:$BOT_PID" >> "$PIDFILE"

echo "Starting cloudflare tunnel..."
BIN="$DIR/bin/cloudflared"
TMPLOG=$(mktemp)
nohup $BIN tunnel --url http://localhost:4096 > "$TMPLOG" 2>&1 &
TUNNEL_PID=$!
echo "tunnel:$TUNNEL_PID" >> "$PIDFILE"

echo "Waiting for tunnel URL..."
for i in $(seq 1 15); do
  URL=$(grep -o 'https://[a-z0-9-]*\.trycloudflare\.com' "$TMPLOG" 2>/dev/null | tail -1)
  [ -n "$URL" ] && break
  sleep 1
done

echo ""
echo "============================================"
echo " ALL SERVICES STARTED"
echo "============================================"
echo " PIDs saved in: $PIDFILE"
echo " OpenCode server : http://localhost:4096"
echo " Telegram bot    : running"
if [ -n "$URL" ]; then
  echo ""
  echo " >>> TUNNEL URL (buka dekat phone):"
  echo " >>> $URL"
  echo " Login: opencode / infaq123"
else
  echo " Tunnel URL      : still loading..."
  echo " Check: tail -f $TMPLOG"
fi
echo "============================================"
echo " To stop: bash $DIR/stop.sh"
echo "============================================"

caffeinate -i -w $$ > /dev/null 2>&1 &

trap "echo 'Stopping...'; pkill -F "$PIDFILE" 2>/dev/null; rm -f "$PIDFILE" $TMPLOG; exit 0" SIGINT SIGTERM

wait
