<style type="text/css">
  .exampleRegisterForward {
    height: 2.85em;
    margin-top: 1em;
    overflow: hidden;
    transition: all 0.4s;
  }

  .exampleRegisterForward.disabled {
    height: 0px;
    padding-bottom: 0px;
    padding-top: 0px;
  }
</style>

<div id="exampleRegisterForwardNote" class="exampleRegisterForward warning disabled">
  <b>{lang}exampleregistrationforward.note.title{/lang}</b>
  <span>{lang}exampleregistrationforward.note.description{/lang}</span>
</div>

<script type="text/javascript">
  (function(){
    // element references
    var no_button = document.getElementById('exampleRegisterForward_no');
    var note      = document.getElementById('exampleRegisterForwardNote');

    // check on page creation
    noteUpdate();

    // listen to form element
    document.getElementById('exampleRegisterForward').addEventListener('click', function(){
      noteUpdate();
    });
    no_button.addEventListener('click', function(){
      noteUpdate();
    });

    // update display of note
    function noteUpdate() {
      if (no_button.matches(':checked')) {
        note.classList.remove('disabled');

      } else {
        note.classList.add('disabled');
      }
    }
  })();
</script>
