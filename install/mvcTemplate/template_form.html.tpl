<script>
    $(function () {
        $("#tabs").tabs();

        $( "#deleteForm" ).submit(function( event ) {
            var result = confirm("Are you sure to delete the record?");
            if (! result)
            {
                event.preventDefault();
            }
        });
    });
</script>

<div id="tabs">
    <ul>
        <li><a href="#tabs-search">Search</a></li>
        <li><a href="#tabs-result">Result</a></li>
        <li><a href="#tabs-edit">Edit</a></li>
        <li><a href="#tabs-add">Add</a></li>
        <li><a href="#tabs-more">More</a></li>
    </ul>

    <div id="tabs-search">
        <h2>Search</h2>

        <form action="/* Name */_action_go.php#tabs-result" method="post">
            <input type="hidden" name="table" value="{$data.tableName}" />
            <input type="hidden" name="action" value="search" />
            {foreach from=$data.cols item=row}
            <p>
                <label for="add_{$row.name}">{$row.name}:</label>
                <input id="add_{$row.name}" name="{$row.name}" type="text" value="" />
            </p>
            {/foreach}
            <input type="submit" value="Search">
        </form>
    </div>

    <div id="tabs-result">
        <h2>Search Result</h2>

        <table border="1">
            <tr>
                {foreach from=$data.cols item=row}
                    <th><a href="/* Name */_form.php?table={$data.tableName}&sort={$row.name}{$data.searchStr}#tabs-result">{$row.name}</a></th>
                {/foreach}
            </tr>
            {foreach from=$data.rows item=row}
            <tr>
                {foreach from=$row item=item name=rowLoop}
                {if $smarty.foreach.rowLoop.first}
                    <td><a href="/* Name */_form.php?table={$data.tableName}&id={$item}#tabs-edit">{$item}</a></td>
                {else}
                    <td>{$item}</td>
                {/if}
                {/foreach}
            </tr>
            {/foreach}
        </table>
        <p>
            Page:
            <a href="/* Name */_form.php?table={$data.tableName}&page={$data.pagingFirst}&sort={$data.sort}{$data.searchStr}#tabs-result">First</a>
            {for $page=$data.pagingStart to $data.pagingEnd}
                | <a href="/* Name */_form.php?table={$data.tableName}&page={$page}&sort={$data.sort}{$data.searchStr}#tabs-result">{$page}</a>
            {/for}
            | <a href="/* Name */_form.php?table={$data.tableName}&page={$data.pagingLast}&sort={$data.sort}{$data.searchStr}#tabs-result">Last</a>
        </p>
        <p><i>Last Update: {$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</i></p>
    </div>

    <div id="tabs-edit">
        <h2>Edit</h2>

        <form action="/* Name */_action_go.php" method="post">
            <input type="hidden" name="table" value="{$data.tableName}" />
            <input type="hidden" name="action" value="edit" />
            {foreach from=$data.cols item=row}
            <p>
                <label for="edit_{$row.name}">{$row.name}:</label>
                <input id="edit_{$row.name}" name="{$row.name}" type="text" value="{$data.edit[$row.name]}" />
            </p>
            {/foreach}
            <input type="submit" value="Edit">
        </form>

        <hr />

        <form action="/* Name */_action_go.php" method="post" id="deleteForm">
            <input type="hidden" name="table" value="{$data.tableName}" />
            <input type="hidden" name="action" value="delete" />
            {foreach from=$data.cols item=row name=rowLoop}
                {if $smarty.foreach.rowLoop.first}
                    <input type="hidden" name="{$row.name}" value="{$data.edit[$row.name]}" />
                {/if}
            {/foreach}
            <input type="submit" value="Delete">
        </form>
    </div>

    <div id="tabs-add">
        <h2>Add</h2>

        <form action="/* Name */_action_go.php" method="post">
            <input type="hidden" name="table" value="{$data.tableName}" />
            <input type="hidden" name="action" value="add" />
            {foreach from=$data.cols item=row}
                <p>
                    <label for="add_{$row.name}">{$row.name}:</label>
                    <input id="add_{$row.name}" name="{$row.name}" type="text" value="" />
                </p>
            {/foreach}
            <input type="submit" value="Add">
        </form>
    </div>

    <div id="tabs-more">
        <h2>More</h2>
        <a href="index.php">Home</a>
    </div>
</div>
