<!--<h1 class="center">Hexagon free</h1>-->
<div class="hexa_free">
    <?php
    for ($i = 0; $i < 30; $i++)
    {
        echo "<div class='hexagon ".((\basics\Utils::randInt(0,1))?"bordered":"")."'></div>";
    }
    ?>
</div>

<script src="res/scripts/modules/hexagon.js"></script>