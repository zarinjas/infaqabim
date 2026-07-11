#!/usr/bin/env bash
DIR="$(cd "$(dirname "$0")" && pwd)"
PIDFILE="$DIR/.all-pids"

if [ ! -f "$PIDFILE" ]; then
  echo "No PID file found. Searching for processes..."
  pkill -f "artisan serve" 2>/dev/null && echo "Killed artisan serve"
  pkill -f "vite" 2>/dev/null && echo "Killed vite"
  pkill -f "opencode serve" 2>/dev/null && echo "Killed opencode serve"
  pkill -f "src/index.js" 2>/dev/null && echo "Killed telegram bot"
  pkill -f cloudflared 2>/dev/null && echo "Killed cloudflared"
  echo "Done."
  exit 0
fi

echo "Stopping all services..."
pkill -F "$PIDFILE" 2>/dev/null
rm -f "$PIDFILE"
echo "All services stopped."
echo ""
echo "To start again: bash $DIR/start-all.sh"
