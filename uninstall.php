<?php

wp_roles()->remove_role('guest_get_certificate');
update_option('dashboardplugincertificate_serialkey', '');
update_option('dashboard_checklicense', '');