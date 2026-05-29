<section class="container my-5">
    <div class="mx-auto" style="max-width: 920px;">
        <div class="text-center mb-4">
            <h1 class="fw-bold">MDU Level Placement</h1>
            <p class="text-muted mb-0">
                Use the main email from your studio account so we can match your family to the correct dancer results.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h4 fw-bold">Returning Families</h2>
                        <p>
                            If you already created an account to view level placements, log in with the same email and password.
                        </p>
                        <a href="{{ route('login') }}" class="btn-brand-primary">Log In</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-body">
                        <h2 class="h4 fw-bold">New Families</h2>
                        <p>
                            If this is your first time viewing placement results, register a new account using the main email from your studio account.
                        </p>
                        <a href="{{ route('register') }}" class="btn-brand-primary">Register</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h2 class="h4 fw-bold">Helpful Tips</h2>
                <ul class="mb-0">
                    <li>If you have multiple dancers, create one parent account using the main studio email.</li>
                    <li>If registration says the email has already been taken, use the Login option instead.</li>
                    <li>If you forgot your password, use Forgot your password? on the login page to request a reset link.</li>
                    <li>Placements only appear for styles your dancer was evaluated for. If you don't see a certain style it means the dancer did not participate in that style this past season.</li>
                    <li>If you do not see your dancer's results, log out and try another email address your family may have used with the studio.</li>
                    <li>After logging in, choose View Level Placement from the dashboard to see each dancer's results and eligible class options.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
