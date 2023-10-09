import Echo from 'laravel-echo'

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: '6e227e25bffc3fe31b50',
  cluster: 'ap1',
  forceTLS: true
});

var channel = Echo.channel('my-channel');
channel.listen('.my-event', function(data) {
  alert(JSON.stringify(data));
});
