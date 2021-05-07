$(function(){
    $(".details-btn").on('click', function(e) {
        $row = $(this).parent().parent();
        $modal = $("#details-modal");

        $modal.data('batchId', $row.data('batchId'));

        $modal.find("span[name=day]").text($row.data('day'));
        $modal.find("span[name=time]").text($row.data('time'));
        $modal.find("span[name=level]").text($row.data('level'));
        $modal.find("span[name=students]").text($row.data('students'));

        $modal.fadeIn();
    });

    $('#add-test-btn').on('click', function(e) {
        window.location.href = 'newtest.php?batch='+$("#details-modal").data("batchId");
    });
});
