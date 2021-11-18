<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId: '741197636634474',
            autoLogAppEvents: true,
            xfbml: true,
            version: 'v7.0'
        });
    };
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>


<script>
    document.getElementById('shareBtn').onclick = function() {
        FB.ui({
            display: 'popup',
            method: 'share',
            hashtag: '#ddddddddddddddd',
            href: 'https://developers.facebook.com/docs/',
        }, function(response) {});
    }
</script>
