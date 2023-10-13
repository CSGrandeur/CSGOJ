{if !$edit_mode || $edit_mode && $item_priv }
<label for="cooperator">Co-editor: </label>
<input type="text" class="form-control" id="cooperator" placeholder="Split with ','. Like: user1,user2... At most 6 co-editors" name="cooperator" {if $edit_mode}value="{$cooperator}"{/if} style="width:100%;">
{/if}