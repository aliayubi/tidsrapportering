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
                <option value="day" selected><?php echo __( 'Dag', 'tidsrapportering' ); ?></option>
                <option value="noon"><?php echo __( 'Kväll', 'tidsrapportering' ); ?></option>
                <option value="night"><?php echo __( 'Natt', 'tidsrapportering' ); ?></option>
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
<form class="get_reports" method="GET">
     <input type="submit" class="button button-primary" value="<?php echo __( 'Få sammantfatnning', 'tidsrapportering' ); ?>">
</form>