document.addEventListener('DOMContentLoaded', function() {
const eventId = /* イベントIDを適切に設定 */;
const commentForm = document.getElementById('comment-form');
const commentContent = document.getElementById('comment-content');
const commentsList = document.getElementById('comments-list');

// コメント一覧を取得
fetch(`/events/${eventId}/comments`)
.then(response => response.json())
.then(data => {
const { comments, userId } = data;
commentsList.innerHTML = comments.map(comment => `
<div id="comment-${comment.id}">
  <p>${comment.user.name}: <span id="comment-content-${comment.id}">${comment.content}</span></p>
  ${comment.user.id === userId ? `
  <button onclick="editComment(${comment.id})">編集</button>
  <form id="edit-form-${comment.id}" style="display: none;">
    <input type="text" id="edit-content-${comment.id}" value="${comment.content}">
    <button type="button" onclick="updateComment(${comment.id})">保存</button>
  </form>
  <button onclick="deleteComment(${comment.id})">削除</button>
  ` : ''}
</div>
`).join('');
});

// コメントを投稿
commentForm.addEventListener('submit', function(event) {
event.preventDefault();

fetch(`/events/${eventId}/comments`, {
method: 'POST',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
},
body: JSON.stringify({
content: commentContent.value
})
})
.then(response => response.json())
.then(comment => {
commentsList.innerHTML += `
<div id="comment-${comment.id}">
  <p>${comment.user.name}: <span id="comment-content-${comment.id}">${comment.content}</span></p>
  ${comment.user.id === userId ? `
  <button onclick="editComment(${comment.id})">編集</button>
  <form id="edit-form-${comment.id}" style="display: none;">
    <input type="text" id="edit-content-${comment.id}" value="${comment.content}">
    <button type="button" onclick="updateComment(${comment.id})">保存</button>
  </form>
  <button onclick="deleteComment(${comment.id})">削除</button>
  ` : ''}
</div>
`;
commentContent.value = '';
});
});

// コメントの更新
window.updateComment = function(commentId) {
const editContent = document.getElementById('edit-content-' + commentId).value;

fetch(`/comments/${commentId}`, {
method: 'PUT',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
},
body: JSON.stringify({
content: editContent
})
})
.then(response => response.json())
.then(comment => {
document.getElementById('comment-content-' + commentId).innerText = comment.content;
document.getElementById('comment-content-' + commentId).style.display = 'block';
document.getElementById('edit-form-' + commentId).style.display = 'none';
})
.catch(error => {
console.error('Error:', error);
});
};

// コメントの削除
window.deleteComment = function(commentId) {
if (!confirm('このコメントを削除してもよろしいですか？')) {
return;
}

fetch(`/comments/${commentId}`, {
method: 'DELETE',
headers: {
'Content-Type': 'application/json',
'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
}
})
.then(response => response.json())
.then(data => {
if (data.success) {
const commentElement = document.getElementById('comment-' + commentId);
commentElement.remove();
} else {
console.error('Error:', data.error);
}
})
.catch(error => {
console.error('Error:', error);
});
};
});