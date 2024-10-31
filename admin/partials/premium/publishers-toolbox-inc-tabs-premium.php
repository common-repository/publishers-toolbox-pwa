<div id="premium-tab-1" class="tab-content">
    <h3>Subscription Details</h3>
    <div class="card">
        <div class="card-header">
            Subscription Domain: <?php echo get_site_url(get_current_blog_id()); ?>
        </div>
        <div class="card-body">
            <?php if (isset($subscriptionOptions['days']) && $subscriptionOptions['days'] <= 0) { ?>
                <div class="notice notice-error inline has-margin-bottom">
                    <p>Your PWA subscription has expired and you are now using the free version, please purchase or activate a subscription.</p>
                </div>
            <?php } ?>
            <?php if (isset($subscriptionOptions['status']) && $subscriptionOptions['status'] !== 'free') { ?>
                <div class="grid has-margin-bottom">
                    <?php if ($subscriptionOptions['days'] > 0 && $subscriptionOptions['days'] !== 0) { ?>
                        <div class="col-1-3 no-margin">
                            <h6>Days left:
                                <span class="color-primary"><?php echo $subscriptionOptions['days']; ?></span></h6>
                        </div>
                    <?php } ?>
                    <?php if ($subscriptionOptions['status']) { ?>
                        <div class="col-1-3 no-margin">
                            <h6>Status:
                                <span class="color-primary"><?php echo $subscriptionOptions['status'] === 'trialing' ? '15 Day Trial' : 'Active Subscription'; ?></span>
                            </h6>
                        </div>
                    <?php } ?>
                    <?php if ($subscriptionOptions['date']) { ?>
                        <div class="col-1-3 no-margin">
                            <h6>Expiry date:
                                <span class="color-primary"><?php echo date('Y-m-d', strtotime($subscriptionOptions['date'])); ?></span>
                            </h6>
                        </div>
                    <?php } ?>
                </div>
                <hr>
                <div class="grid has-margin-top">
                    <div class="col-1-2 is-center-align">
                        <a href="https://www.publisherstoolbox.com/websuite/contact-us/" rel="noopener noreferrer" class="btn is-primary" target="_blank">WebSuite Support</a>
                    </div>
                    <div class="col-1-2 is-center-align">
                        <a href="https://www.publisherstoolbox.com/websuite/cancellation/" rel="noopener noreferrer" class="btn is-destructive cancel-subscription" target="_blank">Cancel Subscription</a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="grid">
                    <div class="col-1-1 single">
                        <p>
                            <strong>WebSuite PWA</strong> plugin provides an app-like experience on the web for mobile users with optimised functionality for content-rich websites. This is achieved using the PWA bundle of web technologies. The Publisher’s Toolbox team have built a PWA framework that is compliant with WordPress and can be themed with various templates.
                        </p>
                        <table class="is-bordered" aria-describedby="Product table">
                            <thead>
                            <tr>
                                <th scope="col">Features</th>
                                <th scope="col">Free</th>
                                <th scope="col">Premium</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Configurable ad units</td>
                                <td>No</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>Push notifications</td>
                                <td>No</td>
                                <td>Yes</td>
                            </tr>
                            <tr>
                                <td>Premium Support</td>
                                <td>No</td>
                                <td>Yes</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="cf"></div>
                    <div class="col-1-2 is-center-align">
                        <a href="https://www.publisherstoolbox.com/shop/" rel="noopener noreferrer" class="btn is-secondary" target="_blank">Purchase Subscription</a>
                    </div>
                    <div class="col-1-2 is-center-align">
                        <a href="#" class="btn is-primary activate-subscription">Activate Subscription</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
