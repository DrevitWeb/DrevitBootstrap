<!--<h1 class="center">Hexagon free</h1>-->
<div class="hexa_free">
    <?php

    use basics\Maths;

    for ($i = 0; $i < 30; $i++)
    {
        $move = Maths::randInt(0,1);
        echo "<div class='hexagon ".((Maths::randInt(0, 1))?"bordered":"")." ".(($move)?"move":"")."' desc='TESTAAAAAAAAA<br/>Test aaaa'>";
        if($move)
        {
            echo "<div class='openedContent'><div class=\"slideshow sliding-left tooltip\">
    <img src=\"res/img/slideshow/1.jpg\" />
    <img src=\"res/img/slideshow/2.jpg\" />
    <img src=\"res/img/slideshow/3.jpg\" />
    <img src=\"res/img/slideshow/4.jpg\" />
    <img src=\"res/img/slideshow/5.jpg\" />
    <img src=\"res/img/slideshow/6.jpg\" />
</div></div>";
            echo "<div class='iconContent'><img src='res/img/slideshow/1.jpg'></div>";
        }
        echo "</div>";
    }
    ?>
</div>

<script src="res/scripts/modules/hexagon.js"></script>
<script src="res/scripts/modules/slideshow.js"></script>