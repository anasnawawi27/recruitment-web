<?php echo isset($toolbar) ? $toolbar : ''; ?>
<div class="card shadow-none mb-0" style="border-radius:0">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table"
                   class="<?php echo(isset($table['class']) ? $table['class'] : ''); ?>"
                   data-url="<?php echo $table['url']; ?>"
                   data-toolbar="#toolbar"
                   data-side-pagination="server"
                   data-pagination="true"
                   data-show-extended-pagination="true"
                   data-icons-prefix="mdi"
                   data-icons="tableIcons"
                   data-search="<?php echo $table['search'] ?? 'true'; ?>"
                   data-show-columns="true"
                   data-minimum-count-columns="1"
                   data-buttons-order="tableButtonsOrder"
                   data-classes="table table-hover"
                   data-cookie="true"
                   data-cookie-id-table="<?php echo $table['cookie_id']; ?>"
                   data-page-list="[10, 25, 50, 100, 500]"
                   data-sticky-header="true"
                   data-sticky-header-offset-y="60"
                   data-query-params="queryParams"
                <?php if(isset($table['sorting'])) { ?>
                    data-sort-name="<?php echo $table['sorting']['field']; ?>"
                    data-sort-order="<?php echo $table['sorting']['order']; ?>"
                <?php } ?>
                <?php if(isset($table['data'])) {
                    foreach($table['data'] as $tableData) {
                        echo 'data-'.$tableData['data'].'="'.$tableData['value'].'"';
                    }
                } ?>
            >
                <thead>
                <tr>
                    <?php
                    foreach($table['columns'] as $column) {
                        echo '<th ';
                        foreach($column as $attr => $val) {
                            switch($attr) {
                                case 'style':
                                    echo ' style="'.$val.'"';
                                case 'class':
                                    echo ' class="'.$val.'"';
                                default:
                                    echo ' data-'.$attr.'="'.$val.'"';
                            }
                        }
                        echo '>'.(isset($column['title']) ?? '').'</th>';
                    }
                    ?>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<?php if(isset($table['form_filter'])) {
    echo $table['form_filter'];
} ?>