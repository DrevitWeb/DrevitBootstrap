<!--<h1 class="center">Hexagon free</h1>-->
<div class="hexa_free">
    <?php

    use basics\Maths;

    for ($i = 0; $i < 30; $i++)
    {
        echo "<div class='hexagon ".((Maths::randInt(0, 1))?"bordered":"")." ".((Maths::randInt(0, 1))?"move":"")."' desc='TESTAAAAAAAAA<br/>Test aaaa'></div>";
    }
    ?>
</div>

<script src="res/scripts/modules/hexagon.js"></script>