<?php
include './inc/dbh.inc.php';
include './inc/Auth/auth.php';
include_once './inc/extra/date.func.php';
require_once 'header.php';

if (!isset($_GET['id'])) {
  echo "<h1 class='text-center'>Comment could not be found. Go <a href='./' class='alert text-info '>back</a></h1>";
  die();
}
$user = isset($_SESSION['userUid']) ? $_SESSION['userUid'] : '';

$post_id = $_GET['id'];
$sql = "SELECT `users`.`uidusers`, `users`.`profile_picture`, `users`.`usersFirstname`, `users`.`usersSecondname`, `comments`.* 
        FROM `users`, `comments` 
        WHERE `comments`.`post_id` ='$post_id' AND ((`uidusers` = `comments`.`user`) OR (`comments`.`user` = 'deleted')) 
        ORDER BY `comments`.`date` DESC";

?>

<link rel="stylesheet" href="css/comment.css">
<div class="row mob-m-0 p-0">
  <div class="col-sm-3 nav-hide sidebar-sticky pt-3">
    <?php
    require "./template/nav.php";
    ?>
  </div>

  <div class="col-sm-9 p-0">
    <div id="app">
      <div class="box mt-4 px-4">
        <input id="comm" class="text-dark" placeholder="Write a comment...">
        <button type="submit" class="btn co" style="background: var(--ho); color: var(--white);" id="submit">Submit</button>
      </div>
      <main id="comments-section" class='mb-4'>
        <?php
        $result = $conn->query($sql);
        $comments = array();

        while ($row = $result->fetch_assoc()) {
          $comments[$row['id']] = $row; // Store comments in an associative array using their IDs
        }

        // Function to recursively render comments and their replies
        function renderComments($comments, $parent_id = null, $indent = 0)
        {
          global $user;
          foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parent_id) {
        ?>
              <div class="comment-container <?= ($comment['parent_id'] !== null) ? 'reply-container' : '' ?>" style="margin-left: <?= $indent ?>px" data-comment-id="<?= ($comment['id'] ?? null) ?>">
                <div class="comment-header">
                  <img src="img/<?= $comment['profile_picture'] ?? 'M.jpg' ?>" class="user-image" loading="lazy">
                  <div class="user-info">
                    <span class="user-name co">
                      <?= $comment["user"] ?>
                    </span>
                    <span class="comment-date">
                      <?= format_date($comment['date']) ?>
                    </span>
                  </div>
                </div>
                <div class="comment-content co">
                  <?= $comment['comment'] ?>
                </div>
                <div class="comment-actions">
                  <a href="#reply" onclick="showReplyForm(<?= $comment['id'] ?>)"><i class="fas fa-reply"></i>
                    Reply</a>
                  <?php
                  if ($comment["user"] == $user) {
                    echo '<a href="#delete" onclick="deleteComment(' . $comment['id'] . ')"><i class="fas fa-trash-alt"></i> Delete</a>';
                  }
                  ?>
                  <a href="./inc/report.inc.php?comment=<?= $comment['id'] ?>"><i class="fas fa-exclamation-triangle"></i> Report</a>
                </div>
              </div>
              <div id="reply-form-<?= $comment['id'] ?>" class="reply-form box w-75 ml-5 mt-2" style="display: none;">
                <input id="reply-comm-<?= $comment['id'] ?>" class="text-dark" placeholder="Write a reply...">
                <button type="submit" class="btn" style="background: var(--ho); color: var(--white);" onclick="postReply(<?= $comment['id'] ?>)">Submit</button>
              </div>
        <?php
              renderComments($comments, $comment['id'], $indent + 15); // Recursive call to render replies
            }
          }
        }

        // Render top-level comments
        renderComments($comments);
        ?>
      </main>
    </div>


  </div>
</div>
<script src="./lib/jquery/jquery.js"></script>
<script>
  if (sessionStorage.getItem('user') == null) {
    sessionStorage.setItem('user', "<?= isset($_SESSION['token']) ? $_SESSION['token'] : null ?>");
    sessionStorage.setItem('name', "<?= isset($_SESSION['userUid']) ? $_SESSION['userUid'] : null ?>");
  };
</script>
<script defer>
  const post_id = '<?= $post_id ?>';

  $(document).ready(function() {
    $('#submit').click(postComment);
    <?php
    if (isset($_GET['act'])) {
      echo 'alert("' . $_GET['act'] . '")';
    }
    ?>
  });

  function postComment() {
    $.ajax({
      type: 'POST',
      url: 'inc/comment.inc.php',
      data: {
        user: sessionStorage.getItem('name'),
        comment: $('#comm').val(),
        id: post_id
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      success: postSuccess,
      error: postError
    });
  }

  function postSuccess(data, textStatus, jqXHR) {
    $('#comm').val("");
    window.location.reload();
  }

  function postError(jqXHR, textStatus, errorThrown) {
    alert('Could not post comment');
  }

  function deleteComment(id) {
    $.ajax({
      type: 'POST',
      url: 'inc/comment.inc.php',
      data: {
        del_comment_id: id
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      success: function(data, textStatus, jqXHR) {
         window.location.reload();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert('Could not delete comment');
      }
    });
  }

  function showReplyForm(commentId) {
    $('#reply-form-' + commentId).toggle();
  }

  function postReply(parentId) {
    var replyInputId = '#reply-comm-' + parentId;
    var replyText = $(replyInputId).val();

    $.ajax({
      type: 'POST',
      url: 'inc/comment.inc.php',
      data: {
        user: sessionStorage.getItem('name'),
        comment: replyText,
        id: post_id,
        parent_id: parentId
      },
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      },
      success: postSuccess,
      error: postError
    });
  }
</script>

<?php
require_once 'footer.php';
?>