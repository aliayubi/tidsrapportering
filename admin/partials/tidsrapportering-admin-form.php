<form class="add_report" method="POST">
    <table class="form-table report_table">
        <tbody>
            <tr>
                <td><label for="person">Person som utfört arbetet</label></td>
                <td><input name="person" type="text" id="person" class="regular-text"></td>
            </tr>
            <tr>
                <td><label>Datum</label></td>
                <td>
                    <span class="date_time_report">Från
                    <input autocomplete="false" type="text" name="date" placeholder="<?php echo __( 'Datum', 'tidsrapportering' ); ?>" class="date start" />
                    <input autocomplete="false" type="text" name="time_start" placeholder="<?php echo __( 'Tid', 'tidsrapportering' ); ?>" class="time start" /> till
                    <input autocomplete="false" type="text" name="date_end" placeholder="<?php echo __( 'Datum', 'tidsrapportering' ); ?>" class="date end" />
                    <input autocomplete="false" type="text" name="time_end" placeholder="<?php echo __( 'Tid', 'tidsrapportering' ); ?>" class="time end" />
                    </span>
                </td>
            </tr>
            <tr>
                <td><label><?php echo __( 'Antal timmer', 'tidsrapportering' ); ?></label></td>
                <td><input type="number" name="total_hours" /></td>
            </tr>
            <tr>
                <td><label><?php echo __( 'Arbetsplats', 'tidsrapportering' ); ?></label></td>
                <td><input type="text" name="work_area" /></td>
            </tr>
            <tr>
                <td><label><?php echo __( 'Uppdrag', 'tidsrapportering' ); ?></label></td>
                <td>
                <select name="task">
                <option value="Dag" selected><?php echo __( 'Dag', 'tidsrapportering' ); ?></option>
                <option value="Kväll"><?php echo __( 'Kväll', 'tidsrapportering' ); ?></option>
                <option value="Natt"><?php echo __( 'Natt', 'tidsrapportering' ); ?></option>
                </select>
                </td>
            </tr>
            <tr>
                <td><label><?php echo __( 'Övriga information', 'tidsrapportering' ); ?></label></td>
                <td>
                    <textarea name="extra_info" cols="60" rows="5"></textarea>
                </td>
            </tr>
        </tbody>
    </table>
    <div class="success">
    </div>
    <p class="submit">
     <input type="submit" class="button button-primary" value="<?php echo __( 'Lägg till tiden', 'tidsrapportering' ); ?>">
    </p>
</form>
<?php
$args_reports = array(
	'post_type' => array('tidsrapportering'),
	'post_status' => array('publish'),
	'posts_per_page' => -1,
	'order' => 'DESC',
);
$reports = new WP_Query( $args_reports );
$persons = array();
if ( $reports->have_posts() ) {
	while ( $reports->have_posts() ) {
        $reports->the_post();
        
        $persons[] = get_post_meta(get_the_ID(), 'person', true);
	}
} else {
}
wp_reset_postdata();
$un_persons = array_unique($persons);
?>
<form class="get_reports" action="/wp-admin/admin-ajax.php?action=get_reports" method="POST">  
<h2>Sammanfattning</h2>
    <table class="form-table report_table">
        <tbody>
            <tr>
                <td><label style="padding-right: 25px;"><?php echo __( 'Välja personen som du vill få sammanfattning för:', 'tidsrapportering' ); ?></label>
                    <select name="person" class="person_select">
                    <?php
                        foreach ($un_persons as $person) {
                    ?>
                    <option value="<?php echo $person; ?>"><?php echo $person; ?></option>      
                    <?php      
                        }
                    ?>
                    </select>
                </td>
                <td>
                <input type="submit" class="button button-primary" value="<?php echo __( 'Ladda ner sammanfattning i PDF format', 'tidsrapportering' ); ?>">
                </td>
            </tr>
        </tbody>
    </table>
</form>