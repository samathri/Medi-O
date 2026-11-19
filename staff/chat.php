<?php
$pageTitle = 'Chats';
require_once __DIR__ . '/../components/staff-header.php';

$convId = (int)($_GET['c'] ?? 0);
// "Message customer" buttons elsewhere link here with ?user=ID — open (or start) their conversation
if (!$convId && !empty($_GET['user'])) {
    $cust = q_one("SELECT id FROM users WHERE id=? AND role='customer'", [(int)$_GET['user']]);
    if ($cust) {
        $convId = (int)q_val('SELECT id FROM chat_conversations WHERE user_id=?', [$cust['id']]);
        if (!$convId) {
            q('INSERT INTO chat_conversations (user_id) VALUES (?)', [$cust['id']]);
            $convId = (int)db()->lastInsertId();
        }
    }
}
$conversations = q_all('SELECT c.*, u.name, u.avatar, u.phone,
                        (SELECT body FROM chat_messages m WHERE m.conversation_id=c.id ORDER BY m.id DESC LIMIT 1) AS last_body
                        FROM chat_conversations c JOIN users u ON u.id=c.user_id
                        ORDER BY c.staff_unread DESC, c.last_message_at DESC');
$activeConv = $convId ? q_one('SELECT c.*, u.name, u.phone FROM chat_conversations c JOIN users u ON u.id=c.user_id WHERE c.id=?', [$convId]) : null;
?>
<h1 class="h3 fw-bold mb-4">Customer chats</h1>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="mo-card overflow-hidden">
      <?php if (!$conversations): ?>
        <div class="mo-empty py-5"><i class="bi bi-chat-dots"></i>No conversations yet</div>
      <?php else: foreach ($conversations as $c): ?>
        <a href="<?= url('staff/chat.php?c=' . $c['id']) ?>"
           class="d-flex gap-3 align-items-center p-3 border-bottom text-decoration-none text-body <?= $convId == $c['id'] ? 'bg-light' : '' ?>">
          <div class="mo-avatar-btn flex-shrink-0" style="pointer-events:none">
            <?php if ($c['avatar']): ?><img src="<?= e($c['avatar']) ?>" alt=""><?php else: ?><span><?= e(strtoupper(mb_substr($c['name'], 0, 1))) ?></span><?php endif; ?>
          </div>
          <div class="flex-grow-1 min-width-0">
            <div class="fw-semibold d-flex justify-content-between">
              <span class="text-truncate"><?= e($c['name']) ?></span>
              <?php if ($c['last_message_at']): ?><small class="text-muted fw-normal"><?= time_ago($c['last_message_at']) ?></small><?php endif; ?>
            </div>
            <div class="small text-muted text-truncate"><?= e($c['last_body'] ?? 'No messages') ?></div>
          </div>
          <?php if ($c['staff_unread']): ?><span class="badge text-bg-warning rounded-pill"><?= (int)$c['staff_unread'] ?></span><?php endif; ?>
        </a>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <div class="col-lg-8">
    <?php if (!$activeConv): ?>
      <div class="mo-card mo-empty h-100 d-flex flex-column justify-content-center">
        <i class="bi bi-arrow-left-circle"></i>Select a conversation to reply
      </div>
    <?php else: ?>
      <div class="mo-card overflow-hidden">
        <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
          <div class="fw-bold"><i class="bi bi-person-circle me-2"></i><?= e($activeConv['name']) ?></div>
          <?php if ($activeConv['phone']): ?>
            <a class="btn btn-sm btn-light border" href="tel:<?= e($activeConv['phone']) ?>"><i class="bi bi-telephone me-1"></i><?= e(pretty_phone($activeConv['phone'])) ?></a>
          <?php endif; ?>
        </div>
        <div class="mo-chat-wrap" style="height:56vh">
          <div class="mo-chat-scroll" id="chatScroll"></div>
          <div class="mo-chat-input">
            <form id="chatForm" class="d-flex gap-2">
              <input class="form-control" id="chatBody" placeholder="Reply as pharmacy…" maxlength="2000" autocomplete="off">
              <button class="btn mo-btn px-3"><i class="bi bi-send"></i></button>
            </form>
          </div>
        </div>
      </div>

      <script>
      const CONV = <?= (int)$activeConv['id'] ?>;
      const scrollBox = document.getElementById('chatScroll');
      let lastId = 0;
      function render(msgs) {
        for (const m of msgs) {
          const div = document.createElement('div');
          // staff view: our team's messages (any staff) on the right
          div.className = 'mo-msg ' + (m.staff ? 'me' : 'them');
          div.innerHTML = `<div class="bubble"></div><div class="meta">${m.mine ? 'You' : m.name} · ${m.time}</div>`;
          div.querySelector('.bubble').textContent = m.body;
          scrollBox.appendChild(div);
          lastId = Math.max(lastId, m.id);
        }
        if (msgs.length) scrollBox.scrollTop = scrollBox.scrollHeight;
      }
      async function poll() {
        try {
          const r = await fetch(`${window.MEDIO.base}/api/chat.php?after=${lastId}&conversation=${CONV}`, {headers: {'X-CSRF': window.MEDIO.csrf}});
          const j = await r.json();
          if (j.ok) render(j.messages);
        } catch {}
      }
      poll();
      setInterval(poll, 3000);
      document.getElementById('chatForm').addEventListener('submit', async e => {
        e.preventDefault();
        const input = document.getElementById('chatBody');
        const body = input.value.trim();
        if (!body) return;
        input.value = '';
        const r = await medioApi('chat.php', {action: 'send', body, conversation_id: CONV});
        if (r.ok) poll(); else MedioToast(r.error || 'Could not send', 'error');
      });
      </script>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>
