<div class="container main">
    <div class="container middlewrap">
        <h1>Admin</h1>
    </div>
</div>
<main class="admin">
    <label class="sr-only" for="theme-select">select menu</label>
    <select id="theme-select" name="theme-select">
        <option disabled selected value style="display:none">select theme</option>
        <?php
        // print out all theme options by reading from the themes directory
        $themes = array_diff(scandir('assets/stylesheets/themes'), array('..', '.'));
        foreach ($themes as $theme) {
            //remove extension
            $theme = preg_replace('/\\.[^.\\s]{3,4}$/', '', $theme);
            echo "<option value='$theme'>$theme</option>";
        }
        ?>
    </select>
</main>

<script src="<?php echo BASE_JS_URL;?>admin.js"></script>
