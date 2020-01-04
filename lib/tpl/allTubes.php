<?php
$fields = $console->getTubeStatFields();
$groups = $console->getTubeStatGroups();
$visible = $console->getTubeStatVisible();

if (!@empty($_COOKIE['tubePauseSeconds'])) {
    $tubePauseSeconds = intval($_COOKIE['tubePauseSeconds']);
} else {
    $tubePauseSeconds = 3600;
}
?>

<section id="summaryTable">
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>name</th>
                        <?php
                        foreach ($fields as $key => $item):
                            $markHidden = !in_array($key, $visible) ? ' class="hide"' : '';
                            ?>
                            <th<?php echo $markHidden ?>  name="<?php echo $key ?>" title="<?php echo $item ?>"><?php echo $key ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ((is_array($tubes) ? $tubes : array()) as $tubeItem): ?>
                        <tr>
                            <td name="<?php echo $key ?>"><a href="./?server=<?php echo $server ?>&tube=<?php echo urlencode($tubeItem) ?>"><?php echo $tubeItem ?></a>
                            </td>
                            <?php $tubeStats = $console->getTubeStatValues($tubeItem) ?>
                            <?php
                            foreach ($fields as $key => $item):
                                $classes = array("td-$key");
                                if (!in_array($key, $visible)) {
                                    $classes[] = 'hide' ;
                                }
                                if (isset($tubeStats[$key]) && $tubeStats[$key] != '0') {
                                    $classes[] = 'hasValue';
                                }
                                $cssClass = '' ;
                                if (count($classes) > 0) {
                                    $cssClass = ' class = "' . join(' ', $classes) . '"' ;
                                }
                                ?>
                                <td<?php echo $cssClass ?>><?php echo isset($tubeStats[$key]) ? $tubeStats[$key] : '' ?></td>
                            <?php endforeach; ?>
                            <td>
                                <a class="btn btn-default btn-sm" href="./?server=<?php echo $server ?>&tube=<?php echo urlencode($tubeItem) ?>&action=kickall&count=<?=$tubeStats['current-jobs-buried']?>" title="Kick all the buried jobs">
                                    <i class="glyphicon glyphicon-forward"></i>
                                    Kick all
                                </a>
                            </td>
                            <td>
                                 <?php if (empty($tubeStats['pause-time-left'])): ?>
                                    <a class="btn btn-default btn-sm" href="./?server=<?php echo $server ?>&tube=<?php echo urlencode($tubeItem) ?>&action=pause&count=-1"
                                       title="Temporarily prevent jobs being reserved from the given tube. Pause for: <?php echo $tubePauseSeconds; ?> seconds">
                                       <i class="glyphicon glyphicon-pause"></i>
                                        Pause
                                    </a>
                                 <?php else: ?>
                                    <a class="btn btn-default btn-sm" href="./?server=<?php echo $server ?>&tube=<?php echo urlencode($tubeItem) ?>&action=pause&count=0"
                                       title="<?php echo sprintf('Pause seconds left: %d', $tubeStats['pause-time-left']); ?>">
                                       <i class="glyphicon glyphicon-play"></i>
                                    Unpause
                                    </a>
                                 <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
