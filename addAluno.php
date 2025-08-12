<script>
numAlunoAdicionar = 1;
$(document).keypress(
function(event){
    if (event.which == '13') {
    event.preventDefault();
    }
});

function procurarAluno(termo){
    var d = document.getElementById("listAluno");
    if (d)
    {
        for(var i = 0; i < d.childNodes.length; i++)
        {
            if (d.childNodes[i].nodeType == 1)
                d.childNodes[i].style.display = d.childNodes[i].innerText.toUpperCase().includes(termo.toUpperCase()) ? "" : "none";
        }
    }
    if(termo.length > 0){
        document.getElementById("listAluno").style.display = "";
    }else{
        document.getElementById("listAluno").style.display = "none";
    }
}
function insereAluno(termo){
    nome = termo.toString().split('(')[0].trim();
    ra = termo.toString().split('(')[1].split(')')[0].trim();

    htmldata = '<div class="aluno"><div class="mui--text-title">'+
        nome + ' ('+ ra + ')' +
        '<a class="mui-btn btn-delete" onclick="this.parentElement.parentElement.remove();numAlunoAdicionar--;avaliaQtde();"><i class="material-icons">delete</i></a>'+
        '<input name="aluno' + numAlunoAdicionar + '" value="' + ra + '" readonly type="hidden">'+
      '</div>'+
    '</div>';

    document.getElementById('containerAluno').innerHTML += htmldata;
    document.getElementById("listAluno").style.display = "none";
    numAlunoAdicionar ++;
    avaliaQtde();
}
function avaliaQtde(){
    if(numMaximoAlunos < numAlunoAdicionar){
        document.getElementById("containerAluno").classList.add('erro');
    }else{
        document.getElementById("containerAluno").classList.remove('erro');
    }
}
</script>
<?php
global $PDO, $SEMESTRE, $DEBUG, $trabalho;
$sql = "
SELECT a.*
FROM `aluno` a
INNER JOIN `aluno_materia` am ON am.idAluno = a.ra AND am.semestre  = '$SEMESTRE'
INNER JOIN `materia` m ON am.idMateria = m.idMateria
INNER JOIN `trabalho` t ON t.idMateria = m.idMateria AND t.idTrabalho = $trabalho
WHERE t.semestre  = '$SEMESTRE'
ORDER BY a.nome 
";
$resultAdd = $PDO->prepare($sql);
$resultAdd->execute();
?>
<div class="mui-textfield" style="margin-bottom:0px">
    <input type="text" id="addAluno" placeholder="Insira um integrante" onkeyup="procurarAluno(this.value)">
</div>
<div class="mui-panel" style="width:400px; display:none;" id="listAluno">
<?php while ($row = $resultAdd->fetch(PDO::FETCH_ASSOC)) {?>
    <a class="mui-btn escolhaAluno" onclick="insereAluno(this.innerText)"><?php echo $row['nome'] ?> (<?php echo $row['ra'] ?>)</a>
<?php } ?>
</div>