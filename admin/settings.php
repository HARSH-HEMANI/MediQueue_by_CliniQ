<?php
$content_page = 'appointment';
ob_start();
?>

<main class="admin-dashboard" style="margin-top:20px;">
    <div class="container">

        <!-- Page Header -->
        <div class="features-header text-center mb-2">
            <h2>System <span>Settings</span></h2>
            <div class="section-divider"></div>
            <p>Central configuration for MediQueue system behavior</p>
        </div>

        <!-- General Settings -->
        <div class="feature-acard mb-2">
            <h5 class="mb-3">General Settings</h5>

            <form>
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Default Token Start Number</label>
                        <input type="number" class="form-control" value="1">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Appointment Slot Duration</label>
                        <select class="form-select">
                            <option>10 minutes</option>
                            <option selected>15 minutes</option>
                            <option>20 minutes</option>
                            <option>30 minutes</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Maximum Daily Tokens (per doctor)</label>
                        <input type="number" class="form-control" value="50">
                    </div>

                </div>
            </form>
        </div>

        <!-- Working Days -->
        <div class="feature-acard mb-2">
            <h5 class="mb-3">Working Days Configuration</h5>

            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex flex-wrap gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Monday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Tuesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Wednesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Thursday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" checked>
                            <label class="form-check-label">Friday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Saturday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Sunday</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emergency Rules -->
        <div class="feature-acard mb-2">
            <h5 class="mb-3">Emergency Handling Rules</h5>

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label>Emergency Priority Level</label>
                    <select class="form-select">
                        <option selected>Highest (Override Queue)</option>
                        <option>High (Insert Next)</option>
                        <option>Medium</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label>Maximum Emergency Cases / Hour</label>
                    <input type="number" class="form-control" value="3">
                </div>

            </div>
        </div>

        <!-- Queue Rules -->
        <div class="feature-acard mb-2">
            <h5 class="mb-3">Queue Behavior</h5>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" checked>
                <label class="form-check-label">
                    Auto-skip absent patients after 3 calls
                </label>
            </div>

            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" checked>
                <label class="form-check-label">
                    Allow walk-in tokens after online slots filled
                </label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">
                    Allow doctors to extend consultation time
                </label>
            </div>
        </div>

        <!-- Save Button -->
        <div class="text-end">
            <button class="hero-btn">
                Save System Settings
            </button>
        </div>

    </div>
</main>

<?php
$content = ob_get_clean();
include './admin-layout.php';
?>