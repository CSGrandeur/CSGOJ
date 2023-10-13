<h1>{$user_id}'s Mailbox</h1>
<ul class="nav nav-tabs">
    <li role="presentation" {if $action == 'mail_inbox' } class="active" {/if}><a href="mail_inbox">Inbox</a></li>
    <li role="presentation" {if $action == 'mail_outbox' } class="active" {/if}><a href="mail_outbox">Outbox</a></li>
    <li role="presentation" {if $action == 'mail_add' } class="active" {/if}><a href="mail_add">Add Mail</a></li>
</ul>