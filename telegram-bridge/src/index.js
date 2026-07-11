import "dotenv/config";
import { Bot } from "grammy";

const TELEGRAM_BOT_TOKEN = process.env.TELEGRAM_BOT_TOKEN;
const OPENCODE_URL = process.env.OPENCODE_URL || "http://localhost:4096";
const OPENCODE_PASSWORD = process.env.OPENCODE_PASSWORD || "";
const ownerId = 390961097;

const MODELS = {
  "chat":     { id: "deepseek-chat",     providerID: "deepseek", label: "DeepSeek Chat" },
  "v4-chat":  { id: "deepseek-v4-flash", providerID: "deepseek", label: "DeepSeek V4 Flash" },
  "v4-flash": { id: "deepseek-v4-flash", providerID: "deepseek", label: "DeepSeek V4 Flash" },
  "v4-pro":   { id: "deepseek-v4-pro",   providerID: "deepseek", label: "DeepSeek V4 Pro" },
  "reasoner": { id: "deepseek-reasoner",  providerID: "deepseek", label: "DeepSeek Reasoner" },
};

let currentModel = MODELS["chat"];

const bot = new Bot(TELEGRAM_BOT_TOKEN);

function authHeaders() {
  if (!OPENCODE_PASSWORD) return { "Content-Type": "application/json" };
  const encoded = Buffer.from(`opencode:${OPENCODE_PASSWORD}`).toString("base64");
  return {
    "Content-Type": "application/json",
    Authorization: `Basic ${encoded}`,
  };
}

function isOwner(ctx) {
  return ctx.from?.id === ownerId;
}

async function apiFetch(path, options = {}) {
  const url = `${OPENCODE_URL}${path}`;
  const resp = await fetch(url, {
    ...options,
    headers: { ...authHeaders(), ...options.headers },
    signal: AbortSignal.timeout(120000),
  });
  if (!resp.ok) {
    const text = await resp.text().catch(() => "");
    throw new Error(`API ${resp.status}: ${text.slice(0, 200)}`);
  }
  const contentType = resp.headers.get("content-type") || "";
  if (contentType.includes("application/json")) {
    return resp.json();
  }
  return { _html: true };
}

async function createSession(prompt) {
  const data = await apiFetch("/api/session", {
    method: "POST",
    body: JSON.stringify({
      title: prompt.slice(0, 80),
      model: { id: currentModel.id, providerID: currentModel.providerID },
    }),
  });
  return data.data.id;
}

async function sendPrompt(sessionId, prompt) {
  const data = await apiFetch(`/session/${sessionId}/message`, {
    method: "POST",
    body: JSON.stringify({
      parts: [{ type: "text", text: prompt }],
    }),
  });

  if (data?.info?.error) {
    const err = data.info.error;
    return `Error: ${err.data?.message || err.message || "API error"}`;
  }

  let reply = "";
  if (data?.parts && data.parts.length > 0) {
    reply = data.parts.map((p) => p.text || p.content || "").join("\n").trim();
  } else if (data?.info?.text) {
    reply = data.info.text;
  }

  if (!reply) {
    reply = "No output";
  }

  if (reply.length > 4000) {
    reply = reply.slice(0, 4000) + "\n\n...(truncated)";
  }

  return reply;
}

bot.command("start", async (ctx) => {
  if (!isOwner(ctx)) return ctx.reply("Akses terhad. Bot untuk kegunaan peribadi.");
  await ctx.reply(
    "OpenCode Telegram Bot\n\n"
    + "Hantar mesej untuk prompt opencode.\n"
    + "/model — tengok/tukar model\n"
    + "/status — server status\n"
    + "/help — arahan"
  );
});

bot.command("help", async (ctx) => {
  if (!isOwner(ctx)) return;
  await ctx.reply(
    "Hantar mesej → opencode proses → reply.\n\n"
    + "/model          — tunjuk model semasa\n"
    + "/model chat     — DeepSeek Chat (default)\n"
    + "/model v4-flash — DeepSeek V4 Flash\n"
    + "/model v4-pro   — DeepSeek V4 Pro\n"
    + "/model reasoner — DeepSeek Reasoner\n"
    + "/status         — server status\n"
    + "Guna tunnel URL untuk tengok web UI."
  );
});

bot.command("model", async (ctx) => {
  if (!isOwner(ctx)) return;
  const arg = ctx.match?.trim().toLowerCase();

  if (!arg) {
    const list = Object.entries(MODELS).map(([key, m]) =>
      key === Object.keys(MODELS).find(k => MODELS[k].id === currentModel.id && MODELS[k].providerID === currentModel.providerID)
        ? `• ${m.label} (${key}) ← aktif`
        : `• ${m.label} (/${key})`
    ).join("\n");
    return ctx.reply(`Model semasa: ${currentModel.label}\n\n${list}`);
  }

  const key = arg.replace(/^\/+/, "");
  const model = MODELS[key];

  if (!model) {
    const available = Object.keys(MODELS).join(", ");
    return ctx.reply(`Model '${arg}' tak ada. Pilih: ${available}`);
  }

  currentModel = model;
  await ctx.reply(`Model ditukar ke: ${model.label}`);
});

bot.command("status", async (ctx) => {
  if (!isOwner(ctx)) return;
  try {
    await apiFetch("/api/session", { method: "POST", body: "{}" });
    await ctx.reply(`Server OK\nModel: ${currentModel.label}`);
  } catch {
    await ctx.reply("Server unreachable.");
  }
});

bot.on("message:text", async (ctx) => {
  if (!isOwner(ctx)) return;

  const text = ctx.message.text;
  if (text.startsWith("/")) return;

  const statusMsg = await ctx.reply(`Processing (${currentModel.label})...`);

  try {
    const sessionId = await createSession(text);
    const reply = await sendPrompt(sessionId, text);
    await ctx.api.editMessageText(ctx.chat.id, statusMsg.message_id, reply);
  } catch (e) {
    await ctx.api.editMessageText(ctx.chat.id, statusMsg.message_id, `Error: ${e.message}`);
  }
});

bot.catch((err) => {
  console.error("Bot error:", err.error);
});

console.log("Telegram bot running...");
bot.start();
