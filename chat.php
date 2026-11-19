<?php
require_once __DIR__ . '/config.php';
$u = require_login();
if (is_staff()) redirect('staff/chat.php');

$pageTitle = 'Chat with pharmacy';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="mo-card overflow-hidden" data-reveal>
        <div class="d-flex align-items-center gap-3 p-3 border-bottom" style="background:var(--green);color:#fff">
          <span class="mo-logo" style="background:rgba(255,255,255,.2);box-shadow:none"><i class="bi bi-chat-dots"></i></span>
          <div>
            <div class="fw-bold">Medi-O Pharmacy team</div>
            <div class="small opacity-75"><i class="bi bi-circle-fill me-1" style="font-size:.5rem;color:#4ade80"></i>
              We usually reply during opening hours: <?= e(setting('pharmacy_hours')) ?></div>
          </div>
        </div>

        <div class="mo-chat-wrap">
          <div class="mo-chat-scroll" id="chatScroll">
            <div class="text-center text-muted small py-4" id="chatEmpty">
              <i class="bi bi-chat-heart fs-2 d-block mb-2"></i>
              Ask us anything about your medicines, prescriptions, or orders.
            </div>
          </div>
          <div class="mo-chat-input">
            <form id="chatForm" class="d-flex gap-2">
              <input class="form-control" id="chatBody" placeholder="Type your message…" maxlength="2000" autocomplete="off">
              <button class="btn mo-btn px-3" aria-label="Send"><i class="bi bi-send"></i></button>
            </form>
          </div>
        </div>
      </div>
      <div class="text-center small text-muted mt-3"> You'll also get an email when we reply.
      </div>
    </div>
  </div>
</div>

<script>
const scrollBox = document.getElementById('chatScroll');
let lastId = 0, empty = true;

function render(msgs) {
  if (!msgs.length) return;
  if (empty) { document.getElementById('chatEmpty')?.remove(); empty = false; }
  for (const m of msgs) {
    const div = document.createElement('div');
    div.className = 'mo-msg ' + (m.mine ? 'me' : 'them');
    div.innerHTML = `<div class="bubble"></div><div class="meta">${m.mine ? 'You' : m.name} · ${m.time}</div>`;
    div.querySelector('.bubble').textContent = m.body;
    scrollBox.appendChild(div);
    lastId = Math.max(lastId, m.id);
  }
  scrollBox.scrollTop = scrollBox.scrollHeight;
}

async function poll() {
  try {
    const r = await fetch(`${window.MEDIO.base}/api/chat.php?after=${lastId}`, {headers: {'X-CSRF': window.MEDIO.csrf}});
    const j = await r.json();
    if (j.ok) render(j.messages);
  } catch { /* offline — retry next tick */ }
}
poll();
setInterval(poll, 3000);

document.getElementById('chatForm').addEventListener('submit', async e => {
  e.preventDefault();
  const input = document.getElementById('chatBody');
  const body = input.value.trim();
  if (!body) return;
  input.value = '';
  const r = await medioApi('chat.php', {action: 'send', body});
  if (r.ok) poll(); else MedioToast(r.error || 'Could not send', 'error');
});
</script>
<?php include __DIR__ . '/components/footer.php'; ?>
