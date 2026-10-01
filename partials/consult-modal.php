<?php
declare(strict_types=1);
?>
<div
    class="consult-modal"
    id="consultModal"
    hidden
    role="dialog"
    aria-modal="true"
    aria-labelledby="consultModalTitle"
    aria-hidden="true"
>
    <div class="consult-modal__backdrop" data-consult-close tabindex="-1"></div>
    <div class="consult-modal__dialog" role="document">
        <button
            type="button"
            class="consult-modal__close"
            data-consult-close
            aria-label="Close consultation form"
        >
            <span aria-hidden="true">&times;</span>
        </button>

        <h2 class="consult-modal__title" id="consultModalTitle">Request Your Consultation</h2>

        <form
            class="consult-modal__form"
            id="consultModalForm"
            method="post"
            action="<?= so_e(so_url('/lead.php')) ?>"
            novalidate
        >
            <div class="consult-modal__field">
                <label for="consult-name">Full Name</label>
                <input
                    id="consult-name"
                    name="name"
                    type="text"
                    autocomplete="name"
                    required
                    placeholder=" "
                >
            </div>

            <div class="consult-modal__field">
                <label for="consult-email">Email Address</label>
                <input
                    id="consult-email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    required
                    placeholder=" "
                >
            </div>

            <div class="consult-modal__field">
                <label for="consult-phone">Phone Number</label>
                <input
                    id="consult-phone"
                    name="phone"
                    type="tel"
                    autocomplete="tel"
                    required
                    placeholder=" "
                >
            </div>

            <p class="consult-modal__status" id="consultModalStatus" role="alert" hidden></p>

            <button class="consult-modal__submit" type="submit">
                Book Appointment <span aria-hidden="true">→</span>
            </button>
        </form>
    </div>
</div>
