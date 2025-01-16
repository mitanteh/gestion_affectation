<template>
    <div class="top-tagbar">
        <div class="container-fluid">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-4 col-9">
                    <div class="fs-14 fw-medium">
                        <i class="bi bi-clock align-middle me-2"></i> <span id="current-time">{{ getCurrentTime() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import moment from 'moment'; // Import Moment.js
import 'moment/dist/locale/fr';

export default {
    methods: {
        getCurrentTime() {
            const time = new Date(); // Get the current date and time
            moment.locale('fr'); // Set the locale to French for the current call
            return moment(time).format('Do MMMM YYYY [|] HH:mm'); // Format it using moment.js with 24-hour format
        }

    },
}
</script>