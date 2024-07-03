<script>
    /**
     * DOMが完全に読み込まれた後に実行される処理
     * @param  void
     * @return void
     */
      document.addEventListener('DOMContentLoaded', function() {
          //いいね機能の変数 
          const likeButton = document.getElementById('like-button');
          const likeToggle = document.getElementById('like-toggle');
          const likeIcon = document.getElementById('like-icon');
          const likeText = document.getElementById('like-text');
          const likeCount = document.getElementById('like-count');
          // コメント機能の変数
          const commentForm = document.getElementById('comment-form');
          const commentContent = document.getElementById('comment-content');
          const commentsList = document.getElementById('comments-list');
          const eventId = likeButton.getAttribute('data-event-id');

          const eventPrice = document.getElementById('price').textContent;

          // Stripe.js
            const style = {};
            const stripe = Stripe('{{ config('services.stripe.key') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', { style: style });
            cardElement.mount('#card-element');          


          //いいね登録時リクエストリクエストを複数送れないようにする
          let isRequestInProgress = false;
     /**
     * Toggle like
     * @param  event
     * @return text
     */
          likeToggle.addEventListener('click', function(event) {
            event.preventDefault();
            
            // リクエスト中は他のリクエストができないようにする
              if (isRequestInProgress) return;
              isRequestInProgress = true;

              likeToggle.classList.add('text-gray-200');  // クリックを無効化するためのクラスを追加

     /**
     * show likes
     * 
     */
              fetch(`/events/${eventId}/like`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  }
              })
              .then(response => response.json())
              .then(data => {      
                  likeButton.setAttribute('data-liked', data.liked);
                  likeCount.innerText = data.likeCount;

                  if (data.liked) {
                      likeIcon.innerText = '❤️';
                      likeText.innerText = 'いいねを取り消す';
                  } else {
                      likeIcon.innerText = '♡';
                      likeText.innerText = 'いいねを押してイベントを応援する';
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
              })
                .finally(() => {
                  isRequestInProgress = false;
                  likeToggle.classList.remove('text-gray-200'); 
              });
          });
     /**
     * show comments
     * 
     */
            fetch(`/events/${eventId}/comments`)
                .then(response => response.json())
                .then(data => {
                    const { comments, userId } = data;
                    commentsList.innerHTML = comments.map(comment => `
                        <article id="comment-${comment.id}" class="border-t p-6 text-base bg-white rounded-lg dark:bg-gray-900">
                            <footer class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">${comment.user.name}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><time pubdate datetime="2022-02-08"
                                            title="February 8th, 2022">${comment.formatted_created_at}</time></p>
                                </div>
                                ${comment.user.id === userId ? `
                                <button onclick="deleteComment(${comment.id})"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                    type="button">
                                    削除
                                </button>
                                 ` : ''}
                            </footer>
                            <p class="text-gray-500 dark:text-gray-400">${comment.content}</p>
                            
                        </article>
                    `).join('');
                });

     /**
     * post comments
     * 
     */
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
                  .then(data => {
                    const { comments, userId } = data;
                    commentsList.innerHTML = comments.map(comment => `
                          <article id="comment-${comment.id}" class="border-t p-6 text-base bg-white rounded-lg dark:bg-gray-900">
                            <footer class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">${comment.user.name}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><time pubdate datetime="2022-02-08"
                                            title="February 8th, 2022">${comment.formatted_created_at}</time></p>
                                </div>
                                ${comment.user.id === userId ? `
                                <button onclick="deleteComment(${comment.id})"
                                    class="inline-flex items-center p-2 text-sm font-medium text-center text-gray-500 dark:text-gray-400 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-50 dark:bg-gray-900 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                                    type="button">
                                    削除
                                </button>
                                 ` : ''}
                            </footer>
                            <p class="text-gray-500 dark:text-gray-400">${comment.content}</p>
                            
                        </article>
                    `).join('');

                    commentContent.value = '';
                    
                });
            });

     /**
     * delete comments
     * 
     */
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


     /**
     * Stripe js(payments)
     * 
     */
            document.querySelector('#payment-form').addEventListener('submit', async (event) => {
            event.preventDefault();
            
              const paymentStatus = document.getElementById('payment-status');
              const paymentMessage = document.getElementById('payment-message');
              const paymentButton = document.getElementById('payment-button');

              paymentStatus.classList.remove('hidden');
              paymentMessage.textContent = '支払い中...';
              paymentButton.disabled = true; // ボタンを非活性にする

             const response = await fetch('/reservations', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        event_id: document.querySelector('#event_id').value,
                        quantity: document.querySelector('#number_of_people').value,
                    }),
             });

             const data = await response.json();
               if (data.clientSecret) {
               //   console.log('client  secret');
                const { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: {
                        card: cardElement,
                        billing_details: {
                            name: 'Customer Name',
                        },
                    },
                });

                if (error) {
                     paymentMessage.textContent = '支払いエラーが発生しました。';
                     paymentButton.disabled = false;
                    console.error(error);
                } else if (paymentIntent.status === 'succeeded') {
                //   console.log('start fetch complete');
                    const completeResponse = await fetch('/complete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            event_id: document.querySelector('#event_id').value,
                            quantity: document.querySelector('#number_of_people').value,
                            payment_intent_id: paymentIntent.id,
                        }),
                    });

                    const result = await completeResponse.json();
                    console.log(result.message);
                    paymentMessage.textContent = '支払いが完了し、チケットを購入しました。メールをご確認ください';
                    paymentButton.disabled = true; // 支払い完了後にボタンを非活性にする
                }
              } else {
                console.log(data.message);
                paymentMessage.textContent = '支払い処理中にエラーが発生しました。';
                paymentButton.disabled = false; // エラーが発生した場合、ボタンを再度有効にする
             }
          });

      }); 
</script>