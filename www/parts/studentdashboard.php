<?php
try {
    $conn = dbConnect();

    $levelQuery = $conn->prepare(
        "SELECT batch.level
        FROM student
        INNER JOIN batch
        ON batch.id=student.batch_id
        WHERE student.id=:id"
    );

    $levelQuery->bindParam(':id', $_SESSION['user']['id']);

    $levelQuery->execute();
    $level = $levelQuery->fetchColumn();

    $marksQuery = $conn->prepare(
        "SELECT marks, 
        testtype.name AS tname, 
        testtype.max_marks AS maxmarks 
        FROM marks 
        INNER JOIN test ON test.id=marks.test_id 
        INNER JOIN testtype ON test.testtype_id=testtype.id 
        WHERE marks.student_id=:id"
    );

    $marksQuery->bindParam(':id', $_SESSION['user']['id']);
    
    $marksQuery->execute();
    $marks = $marksQuery->fetchAll(PDO::FETCH_ASSOC);

    $series = "";
    $labels = "";

    foreach ($marks as $mark) {
        $series .= intval(100*$mark['marks']/$mark['maxmarks']).",";
        $labels .= '"'.$mark['tname'].'",';
    }
    $series = substr($series, 0, -1);
    $labels = substr($labels, 0, -1);

} catch (PDOException $e) {
    error_log("[".date('Y-m-d H:i:s O',time())."] [".basename(__FILE__)."] [session: " . json_encode($_SESSION) . "] [input: " . json_encode($_REQUEST) . "] " . $e->getMessage(), 3, "../logs/db_error.log");
    // redirectTo('error');
    exit();
}
?>

<h2 class="fancy span6">Dashboard</h2>

<div class="grid span6" id="stats">
    <div class="card small span2 gradient1">
        <h3>Level</h3>
        <div class="radial chart" data-label="<?php echo $level ?>" data-progress=<?php echo $level*12.5 ?>></div>
    </div>
    <div class="card small span2 gradient2">
        <h3>Practice Avg</h3>
        <div class="radial chart" data-label="84%" data-progress=84></div>
    </div>
    <div class="card small span2 gradient3">
        <h3>Final Avg</h3>
        <div class="radial chart" data-label="68%" data-progress=68></div>
    </div>
    <div class="card span6" id="marks">
        <h3>Marks</h3>
        <div class="line chart" data-series='[<?php echo $series; ?>]' data-labels='[<?php echo $labels; ?>]'></div>
    </div>
</div>