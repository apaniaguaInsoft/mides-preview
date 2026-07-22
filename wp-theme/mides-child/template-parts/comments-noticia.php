<?php
/**
 * Sección de comentarios — diseño MIDES.
 */
if ( post_password_required() ) return;

$post_id  = get_the_ID();
$comments = get_comments( [
	'post_id' => $post_id,
	'status'  => 'approve',
	'order'   => 'ASC',
] );
$count = count( $comments );
?>
<section class="article-comments">
  <div class="container">

    <h2 class="comments__title">
      Comentarios<?php if ( $count ) echo ' <span style="font-size:1rem;font-weight:400;color:#718096">(' . $count . ')</span>'; ?>
    </h2>

    <?php if ( $count > 0 ) : ?>
      <div class="comments__list">
        <?php wp_list_comments( [
          'callback'    => 'mides_comment_template',
          'style'       => 'div',
          'avatar_size' => 0,
        ], $comments ); ?>
      </div>
    <?php else : ?>
      <p style="color:#718096;font-size:0.9rem;margin-bottom:32px">Sé el primero en comentar.</p>
    <?php endif; ?>

  </div><!-- .container -->

  <?php if ( comments_open( $post_id ) ) : ?>
  <div class="container">
    <div class="comments__form-wrap">
    <?php comment_form( [
        'title_reply'          => 'Deja un comentario',
        'title_reply_before'   => '<h3 class="comments__form-title">',
        'title_reply_after'    => '</h3>',
        'logged_in_as'         => '',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'class_form'           => 'comments__form',
        'class_submit'         => 'comments__submit',
        'label_submit'         => 'Publicar comentario',
        'submit_button'        => '<button type="submit" id="comment-submit" class="comments__submit">Publicar comentario</button>',
        'submit_field'         => '<div class="comments__submit-wrap">%1$s %2$s</div>',
        'fields' => [
          'author' => '<div class="comments__form-row"><div class="comments__field">
            <label for="author" class="comments__label">Nombre</label>
            <input type="text" id="author" name="author" class="comments__input" placeholder="Tu nombre" required />
          </div>',
          'email'  => '<div class="comments__field">
            <label for="email" class="comments__label">Correo electrónico</label>
            <input type="email" id="email" name="email" class="comments__input" placeholder="tucorreo@ejemplo.com" required />
          </div></div>',
          'url'    => '',
          'cookies'=> '',
        ],
        'comment_field' => '<div class="comments__field">
          <label for="comment" class="comments__label">Comentario</label>
          <textarea id="comment" name="comment" class="comments__textarea" rows="5" placeholder="Escribe tu comentario aquí…" required></textarea>
        </div>',
        'id_form'   => 'comment-form',
        'id_submit' => 'comment-submit',
      ], $post_id ); ?>
    </div><!-- .comments__form-wrap -->
  </div><!-- .container -->
  <?php else : ?>
  <div class="container">
    <p style="color:#718096;font-size:0.9rem">Los comentarios están cerrados para esta publicación.</p>
  </div>
  <?php endif; ?>

</section>

<div id="comment-toast" role="alert" aria-live="assertive" style="
  display:none;position:fixed;bottom:32px;left:50%;transform:translateX(-50%);
  min-width:280px;max-width:420px;padding:14px 20px;border-radius:10px;
  font-family:'Altivo',sans-serif;font-size:0.92rem;font-weight:600;
  box-shadow:0 8px 32px rgba(0,0,0,.18);z-index:9999;text-align:center;
  transition:opacity .3s;
"></div>

<script>
(function () {
  var form  = document.getElementById('comment-form');
  var btn   = document.getElementById('comment-submit');
  var toast = document.getElementById('comment-toast');
  if (!form || !btn) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    btn.disabled    = true;
    btn.textContent = 'Publicando…';

    fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      credentials: 'same-origin',
      redirect: 'follow',
    })
    .then(function (res) {
      if (res.status === 409) {
        showToast('Ya enviaste este comentario anteriormente.', 'error');
        resetBtn();
      } else if (res.ok || res.redirected) {
        showToast('¡Comentario enviado! Actualizando página…', 'success');
        form.reset();
        setTimeout(function () { window.location.reload(); }, 2000);
      } else {
        showToast('Ocurrió un error. Por favor intenta de nuevo.', 'error');
        resetBtn();
      }
    })
    .catch(function () {
      showToast('Error de red. Por favor intenta de nuevo.', 'error');
      resetBtn();
    });
  });

  function resetBtn() {
    btn.disabled    = false;
    btn.textContent = 'Publicar comentario';
  }

  function showToast(msg, type) {
    toast.textContent = msg;
    toast.style.background  = type === 'success' ? '#276749' : '#c53030';
    toast.style.color       = '#fff';
    toast.style.display     = 'block';
    toast.style.opacity     = '1';
    clearTimeout(toast._timer);
    toast._timer = setTimeout(function () {
      toast.style.opacity = '0';
      setTimeout(function () { toast.style.display = 'none'; }, 300);
    }, 4000);
  }
})();
</script>
