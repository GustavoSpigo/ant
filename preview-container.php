<script>
    function exibir(e){
        lala = e;
        if(e.src == e.src.replace("right","left")){
            e.previousElementSibling.style.display="none";
            e.src = e.src.replace("left","right");
        }else{
            e.previousElementSibling.style.display="inline-block";
            e.src = e.src.replace("right","left");
        }
        
    }
</script>
<div>
    <?php
    echo retornaIconesPadrao($files);
    ?>
</div>