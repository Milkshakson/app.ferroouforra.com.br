
const secondInMiliSeconds = 1000;
const timeToLoadInfoLiveInMinutes = 60 * secondInMiliSeconds;
isLoadingInfoLive = false;
$(document).ready(function () {
    const reloadInfoLive = setInterval(() => {
        if (!isLoadingInfoLive)
            $.ajax({
                beforeSend: () => isLoadingInfoLive = true,
                url: '/streamer/liveInfo',
                success: function (data) {
                    $('#live-time').html(data)
                }
            }).always(() => isLoadingInfoLive = false)
    }, timeToLoadInfoLiveInMinutes);
})