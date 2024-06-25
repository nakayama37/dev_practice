<script>
  document.addEventListener('DOMContentLoaded', function() {
    const postcodeInput = document.getElementById('postcode');
    const prefectureInput = document.getElementById('prefecture');
    const cityInput = document.getElementById('city');
    const streetInput = document.getElementById('street');
    const loadingMessage = document.getElementById('loading-message');

    postcodeInput.addEventListener('input', function() {
      const postcode = postcodeInput.value.replace('-', ''); // ハイフンを削除して7桁の郵便番号に統一
        if (postcode.length === 7) {
          loadingMessage.innerText = '住所取得中';
            fetch(`/api/get-address/${postcode}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
              loadingMessage.innerText = ''; 
                if (data.status === 200) {
                    
                    prefectureInput.value = data.results.prefecture;
                    cityInput.value = data.results.city;
                    streetInput.value = data.results.street;
                } else {
                    alert('住所が見つかりません');
                }
            })
            .catch(error => {
              loadingMessage.innerText = ''; 
                console.error('Error:', error);
                alert('エラーが発生しました');
            });
        }
    });
});
</script>