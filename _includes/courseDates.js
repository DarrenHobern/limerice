<script>
var today = new Date();
var classDates = [];
classDates.push(new Date("2021-03-16"));
classDates.push(new Date("2021-03-30"));
classDates.push(new Date("2021-04-13"));
classDates.push(new Date("2021-04-27"));
classDates.push(new Date("2021-05-11"));
classDates.push(new Date("2021-05-25"));

var i;
for (i = 0; i < classDates.length; i++) {
  if(classDates[i] > today){

    document.querySelectorAll('.cdNext').forEach(el=> el.innerHTML= classDates[i].toLocaleDateString("en-NZ"));
    if(i < classDates.length-1){
      var x = i + 1;

      document.querySelectorAll('.cdUpcoming').forEach(el=> el.innerHTML= classDates[x].toLocaleDateString("en-NZ"));
    }
    break;
  }
}
if(classDates[classDates.length-1] < today){
    document.querySelectorAll('.classDates').forEach(el=> el.remove());
}

  //  document.getElementsByClassName("cdNext").innerHTML = classDates[i].toLocaleDateString("en-NZ");
  //document.getElementById("cdUpcoming").innerHTML = classDates[x].toLocaleDateString("en-NZ");
</script>
