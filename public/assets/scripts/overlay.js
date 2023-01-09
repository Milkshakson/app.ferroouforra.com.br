
const secondInMiliSeconds = 1000;
const timeToLoadInfoLiveInMinutes = 60 * secondInMiliSeconds;
isLoadingInfoLive = false;
isLoadingSummary = false;
$(document).ready(function () {
    reloadSummary();
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


function reloadSummary() {
    $.ajax({
        beforeSend: () => isLoadingSummary = true,
        url: '/streamer/summary',
        success: function (data) {
            $('.year-summary').html(data);
        }
    }).always(() => isLoadingSummary = false)
}