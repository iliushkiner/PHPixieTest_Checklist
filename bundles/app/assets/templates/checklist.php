<?php
// Define parent template
$this->layout('app:layout');

// Set page title
$this->set('pageTitle', "Checklist");
?>

<div class="container content">
    <h1>Чеклист технического аудита сайта</h1><br>    
    <div class="clearfix"></div><br>
    <?php if($user): ?>
        <form id="checklistForm">
            <div class="form-group">
                <?php foreach($checklist as $input): ?>
                <div class="checkboxes row">
                    <div class="col form-check">
                        <input class="form-check-input" type="checkbox" <?=(array_key_exists($_($input->id), $usercheck) ? 'checked ' : '')?>name="check[]" value="<?=$_($input->id)?>" id="flexCheckDefault_<?=$_($input->id)?>">
                        <label class="form-check-label" for="flexCheckDefault">
                            <?=$_($input->name)?>
                        </label>
                    </div>
                    <div class="col-md-auto">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapseChecklist_<?=$_($input->id)?>" aria-expanded="true" aria-controls="collapseChecklist_<?=$_($input->id)?>">            
                            +
                        </button>
                    </div>
                </div>        
                <div id="collapseChecklist_<?=$_($input->id)?>" class="collapse row" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <p><?=$_($input->descr)?></p>
                        <?php foreach($input->child as $child): ?>
                            <div class="col form-check">
                                <input class="form-check-input" type="checkbox" <?=(array_key_exists($_($child->id), $usercheck) ? 'checked ' : '')?>name="check[]" value="<?=$_($child->id)?>" id="flexCheckDefault_<?=$_($child->id)?>">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <?=$_($child->name)?>
                                </label>
                            </div>                        
                        <?php endforeach; ?>
                    </div>
                </div>        
                <?php endforeach; ?>
                <div class="form-control-feedback error"></div>
            </div>
            <p class="text-right">
                <button type="submit" class="btn btn-primary float-right">Применить</button>
            </p>
        </form>
        <div class="clearfix"></div>
        <hr/>
    <?php endif; ?>        
    
</div>

<!-- Add our own scripts to the scripts block defined in the layout.php template -->
<?php $this->startBlock('scripts'); ?>
<script>
    $(function() {
        // Init the form handler
        <?php $url = $this->httpPath('app.action', ['processor' => 'checklist', 'action' => 'post']);?>
        $('#checklistForm').checklistForm("<?=$_($url)?>");
    });
</script>
<?php $this->endBlock(); ?>
