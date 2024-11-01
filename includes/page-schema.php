<?php
echo "<h1>Schema opties</h1>";
if(get_option("toggle-schema-options")) {
    ?>
    <div class="wrap schema-options">
        <p>Dit is waar je de schema.org gegevens invult in de vorm van "JSON-LD". Alle informatie over hoe dit aangevult dient te worden vind je <a href="https://schema.org/address" target="_blank">hier</a>. </p>
        <!-- run the settings_errors() function here. -->
        <?php settings_errors();?>
    <div class="container-form">

    <form method="post" action="options.php" class="siw_wp_ajax_save">
            <?php
            settings_fields("section_schema");
            do_settings_sections("theme-options-schema");
            submit_button();
            submit_button(__('Op de oude manier opslaan', 'siw-to'), 'submit-backup');
            ?>
        </form>
    </div>
        <div class="container-code">
            <h3>Voorbeeld code</h3>
        <pre class="language-php"><code class="language-php">{
    "@context": "http://schema.org",
    "@type": "LocalBusiness",
    "address": {
    "@type": "PostalAddress",
    "addressLocality": "Alkmaar",
    "addressRegion": "Noord-Holland",
    "streetAddress": "Egelskoog 2"
    },
    "description": "Website of webshop laten maken voor het MKB? Specialist in Websites is hét full service internetbureau uit Alkmaar. Jouw online strategisch partner.",
    "name": "Specialist in Websites",
    "telephone": "072-2010300",
    "image": "https://www.specialistinwebsites.nl/wp-content/uploads/2018/08/siw-logo.svg",
    "email": "info@specialistinwebsites.nl"
    }</code></pre>
        </div>
    </div>
    <?php
}




