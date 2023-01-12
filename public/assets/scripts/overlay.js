
const secondInMiliSeconds = 1000;
const timeToLoadInfoLiveInMinutes = 60 * secondInMiliSeconds;
isLoadingInfoLive = false;
isLoadingSummary = false;
isLoadingTournamentList = false;
$(document).ready(function () {
    reloadSummary();
    realoadTournamentList();
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

function realoadTournamentList() {
    $.ajax({
        beforeSend: () => isLoadingTournamentList = true,
        url: '/streamer/tournamentList',
        dataType: 'JSON',
        success: function (data) {
            $('.tournament-list').html(data.html);
        }
    }).always(() => {
        isLoadingTournamentList = false
        $("#carousel-tournaments").carousel();
        setInterval(() => realoadTournamentList(), timeToLoadInfoLiveInMinutes);

    }
    )
}
function reloadSummary() {
    $.ajax({
        beforeSend: () => isLoadingSummary = true,
        url: '/streamer/summary',
        success: function (data) {
            $('.year-summary').html(data);
        }
    }).always(() => {
        isLoadingSummary = false
        setInterval(() => reloadSummary(), timeToLoadInfoLiveInMinutes);
    }
    )
}