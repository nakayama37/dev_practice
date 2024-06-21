<script>
  function cancelPost(e) {
          if(confirm('本当にキャンセルしてもよろしいですか？')) {
            document.getElementById('cancel_'+ e.dataset.id).submit();
          }
        }
</script>