<style>
    :root {
        --mdu-red: #d71945;
        --mdu-red-dark: #ad1638;
        --mdu-blue: #0076b6;
        --mdu-blue-dark: #005f93;
        --mdu-ink: #263044;
    }

    .btn-brand-primary,
    .btn-brand-secondary,
    .btn-brand-danger,
    .brand-link-button {
        align-items: center;
        border-radius: 999px;
        display: inline-flex;
        font-weight: 700;
        justify-content: center;
        line-height: 1.2;
        padding: 0.7rem 1.25rem;
        text-decoration: none;
        transition: background-color 150ms ease, border-color 150ms ease, box-shadow 150ms ease, color 150ms ease, transform 150ms ease;
    }

    .btn-brand-primary {
        background: var(--mdu-red);
        border: 1px solid var(--mdu-red);
        color: #fff;
    }

    .btn-brand-primary:hover,
    .btn-brand-primary:focus {
        background: var(--mdu-red-dark);
        border-color: var(--mdu-red-dark);
        color: #fff;
        box-shadow: 0 0.5rem 1.25rem rgba(215, 25, 69, 0.22);
        transform: translateY(-1px);
    }

    .btn-brand-secondary {
        background: #fff;
        border: 1px solid var(--mdu-blue);
        color: var(--mdu-blue);
    }

    .btn-brand-secondary:hover,
    .btn-brand-secondary:focus {
        background: var(--mdu-blue);
        border-color: var(--mdu-blue);
        color: #fff;
        box-shadow: 0 0.5rem 1.25rem rgba(0, 118, 182, 0.18);
        transform: translateY(-1px);
    }

    .btn-brand-danger {
        background: #fff;
        border: 1px solid var(--mdu-red);
        color: var(--mdu-red);
    }

    .btn-brand-danger:hover,
    .btn-brand-danger:focus {
        background: var(--mdu-red);
        border-color: var(--mdu-red);
        color: #fff;
    }

    .brand-link {
        color: var(--mdu-blue);
        font-weight: 700;
        text-decoration: none;
    }

    .brand-link:hover,
    .brand-link:focus {
        color: var(--mdu-blue-dark);
        text-decoration: underline;
    }

    .brand-link-button {
        background: #f4f8ff;
        border: 1px solid rgba(0, 118, 182, 0.18);
        color: var(--mdu-blue);
        padding: 0.55rem 1rem;
    }

    .brand-link-button:hover,
    .brand-link-button:focus {
        background: var(--mdu-blue);
        border-color: var(--mdu-blue);
        color: #fff;
        text-decoration: none;
    }
</style>
