@extends('frontend.layouts.app')
@section('page-title')
    Terms and Conditions
@endsection
@push('css')
    <style>
        #termsModal p {
            font-size     : 13px;
            margin-bottom : 0.5rem;
        }

        li {
            font-size : 13px;
            margin-bottom : 5px;
        }
    </style>
@endpush
@section('content')

<div>
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms Conditions</h5>
                <p>Last Updated: March 23, 2021</p>
            </div>
            <div class="modal-body">
                <h5 class="my-3">PLEASE READ THESE TERMS OF SERVICE CAREFULLY.</h5>
                <p>
                    Codibu offers a variety of services to customers that range from hosting and website services
                    (including shared, VPS, and dedicated hosting, WordPress hosting, Remixer, email, cloud services,
                    and domain registration services among others. These services are collectively referred to as
                    “Services.”
                    <span class="mb-2 d-block"></span>
                    By using Codibu’s Services, Customer agrees to these Terms of Service and any other policies or
                    terms referenced or published by us (collectively, the “Terms”). If Customer does not accept these
                    Terms, Customer must not register an account or purchase, access, or use Codibu’s Services in any
                    way.
                    <span class="mb-2 d-block"></span>
                    These Terms form a binding legal agreement between Codibu and any person or organization who
                    purchases, accesses, or uses Codibu’s Services (“Customer” or “You”). Customer represents, warrants,
                    and agrees that (a) Customer has the full power and authority to enter into and perform under these
                    Terms, or (b) if Customer is using these Services on behalf of an organization, entity, or group,
                    that Customer is authorized to accept these Terms.
                </p>
                <h6 class="my-3">Financial Arrangements</h6>
                <p>
                    Customer agrees to a thirty (30) day contract minimum beginning upon commencement of service. Exact
                    contract duration is decided upon at signup.
                    <br>
                    Customer agrees that all charges and fees associated with an account are their sole responsibility.
                    <br>
                    If the customer wishes to cancel Codibu shared Web Hosting services within ninety-seven (97) days of
                    the initial signup they shall be able to do so for any reason (aside from disablement for Terms of
                    Service or Spam Policy violations) and have their money promptly refunded.
                    <br>
                    Services provided by 3rd parties and Codibu partners are not part of the 97-day refund policy and no
                    early ending credit applies. Billing will stop at end of term during which the service is canceled.
                    AUTOMATIC RENEWAL: At the end of the contract term, the contract will automatically renew at the
                    then-current, non-promotional rate for the original contract length indefinitely until canceled.
                    <br>
                    AUTOMATIC RENEWAL: At the end of the contract term, the contract will automatically renew at the
                    then-current, non-promotional rate for the original contract length indefinitely until canceled.
                </p>
                <p>
                    Codibu will automatically renew the applicable service when it comes up for renewal and will take
                    payment from the payment method associated with your account. If you do not wish for any service to
                    automatically renew, you may elect to cancel that service, in which case, your Services will
                    terminate upon expiration of the then-current term, unless you manually renew your Services before
                    that date. Any Services must be canceled prior to their renewal date(s) in order to avoid automatic
                    renewals. Renewal dates take effect at midnight, PST.
                </p>
                <p>
                    If the customer’s payment card is determined to be expired, invalid, or otherwise not able to be
                    charged, the customer agrees that Codibu may use other payment methods linked to the customer’s bank
                    account (such as new credit card credentials from your card issuer). If a shared hosting service is
                    terminated after the 97-day money-back guarantee period, the service will be canceled before the
                    next payment is due and no more billing will occur nor will any payment be refunded, even for unused
                    portions. If any non-refundable charges have already been incurred, they must be paid before the
                    account may be canceled.
                </p>
                <p>
                    You can cancel by accessing the Manage Account panel page. Detailed and up-to-date cancellation
                    instructions can be found here.
                    The “97-Day Money-Back Guarantee” offer is only applicable to credit card payments for shared web
                    hosting. Other forms of payment are non-refundable. Refunds can only be processed for shared
                    hosting. Domain registrations (including the value of any used free domain registrations included
                    with the plan) are not refundable under any circumstances. SSL certificates are non-refundable. The
                    value of any AdWords credit or other third-party add-ons is non-refundable. See the Domain
                    Registration Terms for more information.
                    <br>
                    Violations of Codibu’s Terms may, at Codibu’s discretion, result in immediate and permanent
                    disablement without refund.
                    <br>
                    Codibu reserves the right to disable or suspend without refund any account or service at any time
                    should it feel, in its sole discretion, that there is a reasonable suspicion that it is being used
                    in violation of any agreed upon terms.
                    <br>
                    Disputed charges (“chargebacks”) associated with any Codibu account, at Codibu’s discretion, may
                    result in immediate and potentially permanent disablement of Services or the full account. Each
                    rejected charge will incur a $50 fee on the account which must be paid before any Services may be
                    reactivated.
                    <br>
                    Codibu reserves the right to modify current service plans, fees, and applicable charges at any time.
                    Services that involve outdated or unsupported features may incur additional maintenance fees. In
                    such situations, Codibu will provide notice to Customer, and will allow Customer to cancel the
                    Services without incurring additional change fees, but no refund will be payable for any fees
                    previously assessed.
                    <br>
                    Codibu will use commercially reasonable efforts to make DreamObjects available 99.9% of the time
                    during each monthly billing cycle. If Codibu is unable to meet this service level, you will be
                    eligible to receive a credit to apply to future DreamObjects billing cycles based upon the
                    Unavailability for that month.
                    <br>
                    For the purposes of this agreement, Unavailability means that either (a) the DreamObjects service is
                    unresponsive, or (b) DreamObjects returns a server error response to valid user requests for more
                    than 60 seconds of consecutive requests.
                    <br>
                    Unavailability that is a result of scheduled maintenance is excluded from these conditions and will
                    not be considered for service credit calculations. Scheduled maintenance is defined as maintenance
                    that is announced at least 5 days in advance, and does not exceed one hour in any month.
                    <br>
                    Service credits will be calculated as a percentage of the bill for the billing cycle that the
                    Unavailability occurred. The percentage for the credit will be calculated by dividing the number of
                    minutes of Unavailability by the total number of minutes in that billing cycle.
                    <br>
                    Service credits must be claimed within 14 days of the Unavailability occurring by submitting a
                    support ticket. Include as much detail as required to document the Unavailability.
                </p>
                <h6 class="my-3">Taxes</h6>
                <p>
                    Codibu shall not be liable for any taxes or other fees to be paid in accordance with or related to
                    purchases made from Customer or Codibu Web Hosting’s server. Customer agrees to take full
                    responsibility for all taxes and fees of any nature associated with such products sold.
                    Material Products
                </p>
                <p>
                    Customer will provide Codibu with material and data in a condition that is “server-ready”, which is
                    in a form requiring no additional manipulation on the part of Codibu. Codibu shall make no effort to
                    validate this information for content, correctness or usability.
                    <br>
                    Use of Codibu’s service requires a certain level of knowledge in the use of Internet languages,
                    protocols, and software. This level of knowledge varies depending on the anticipated use and desired
                    content of Customer’s Webspace by the Customer.
                    <br>
                    <small>The following examples are offered:</small>
                    Web Publishing: requires a knowledge of HTML, properly locating and linking documents, FTPing
                    Webspace contents, Graphics, text, Sound, imagemapping, etc.
                    <br>
                    CGI-Scripts: requires a knowledge of the UNIX environment, TAR & GUNZIP commands, Perl, CShell
                    scripts, permissions, etc.
                    <br>
                    The Customer agrees that they have the necessary knowledge to create Customer’s Webspace. Customer
                    agrees that it is not the responsibility of Codibu to provide this knowledge or Customer Support
                    outside of the defined service of Codibu.
                    <br>
                    Codibu will exercise no control whatsoever over the content of the information passing through the
                    network, provided that it adheres to all other conditions set forth in our Terms of Service and
                    Acceptable Use Policy documents.
                    <br>
                    Codibu reserves the right to police its network to verify compliance with all agreed upon Terms.
                    <br>
                    The Customer agrees to cooperate in any reasonable investigations into their adherence to all agreed
                    upon Terms. Failure to cooperate is grounds for immediate disablement of all accounts/service plans.
                    <br>
                    Codibu reserves the right to disconnect any website or server deemed to present a security threat to
                    Codibu’s customers, servers, or network.
                    The opening of multiple accounts or service plans in order to bypass any restrictions or overage
                    charges set forth by Codibu is grounds for termination of all Services.
                    <br>
                    Codibu makes no warranties or representations of any kind, whether expressed or implied for the
                    service it is providing. Codibu also disclaims any warranty of merchantability or fitness for a
                    particular purpose and will not be responsible for any damages that may be suffered by the Customer,
                    including loss of data resulting from delays, non-deliveries or service interruptions by any cause
                    or errors or omissions of the Customer. Use of any information obtained by way of Codibu is at the
                    Customer’s own risk, and Codibu specifically denies any responsibility for the accuracy or quality
                    of information obtained through its Services. Any mention of connection speeds associated with
                    Codibu’s Services represents the maximum achievable speed. Codibu does not guarantee that the
                    customer will achieve the maximum connection speed at all times, as this depends on a variety of
                    factors (including your own internet connection!). Codibu expressly limits its damages to the
                    Customer for any non-accessibility time or other down time to the pro-rata monthly charge during the
                    system unavailability.
                    <br>
                    All domain names registered through Codibu or its previous domain registration site,
                    domainitron.com, that are ‘parked’ or are otherwise not immediately associated with a Codibu hosting
                    plan will be automatically pointed to a “Coming Soon” web page which informs visitors that the
                    registrant has recently registered their domain name via
                    <a href="/">codibu.com</a>. The Coming Soon web page may be modified at any time by Codibu without
                    prior notice to you and may include such things as, without limitation, links to additional products
                    and Services offered by Codibu.
                </p>
                <h6 class="my-3">Trademarks & Copyrights</h6>
                <p>
                    Customer warrants that it has the right to use the trademarks and copyrights applicable to all
                    content and/or products being made available through the customer’s account.
                </p>
                <h6 class="my-3">Hardware, Equipment, & Software</h6>
                <p>
                    The customer is responsible for and must provide all telephone, computer, hardware and software
                    equipment and services necessary to access Codibu Services.
                    <br>
                    Codibu makes no representations, warranties or assurances that the Customer’s equipment will be
                    compatible with Codibu Services.
                </p>
                <h6 class="my-3">Guaranteed Uptime</h6>
                <p>
                    Codibu guarantees 100% uptime. A failure to provide 100% uptime will result in customer compensation
                    pursuant to guidelines established herein.
                    Customer is entitled to compensation if Customer’s web site, databases, email, FTP, SSH or webmail
                    become unusable as a result of failure(s) in Codibu systems for reasons other than previously
                    announced scheduled maintenance, coding or configuration errors on the part of the Customer.
                    <br>
                    Customer will receive Codibu credit equal to the Customer’s current hosting cost for 1 (one) day of
                    service for each 1 (one) hour (or fraction thereof) of service interruption, up to a maximum of 10%
                    of Customer’s next pre-paid hosting renewal fee.
                    <br>
                    Codibu’s assessment of downtime begins when Customer opens a support ticket to report the problem.
                </p>
                <h6 class="my-3">Codibu Dedicated Server and DreamCompute Stipulations</h6>
                <p>
                    Bandwidth pricing and measurement frequency are subject to change at Codibu’s discretion. Customers
                    affected by such changes will be notified no less than thirty (30) days in advance by Codibu.
                    <br>
                    Codibu is under no obligation to compensate Customer for downtime, whether the downtime is caused by
                    Customer, Codibu, or Codibu’s upstream providers.
                    <br>
                    Customer agrees that dedicated server payments are NONREFUNDABLE. For example, if Customer submits
                    payment for twelve (12) months of service, service will be provided for twelve (12) months and will
                    not be refunded if Customer chooses to discontinue service with Codibu mid-way through the term.
                    DreamCompute services will be billed on a monthly basis; existing DreamCompute customers may remain
                    on a pre-paid billing plan, but new customer accounts will be subject to a usage-billing plan.
                    Termination or upgrades of DreamCompute services will result in the following refund and billing
                    procedures:
                    <br>
                    Pre-Paid Billing DreamCompute Plan: Any termination or upgrade will result in a refund of the
                    prorated value of the plan for the last billing period.
                    Usage-Billing DreamCompute Plan: Any termination will result in the customer being billed for usage,
                    rounded up to the nearest hour.
                    <br>
                    Hardware upgrades to an existing ‘platform’ (platform defined as a motherboard/chassis combination)
                    will be performed by Codibu and shall incur an additional one-time labor fee of $100 as well as an
                    increase to Customer’s standard monthly rate.
                    <br>
                    Any hands-on labor necessitated by the customer (including, but not limited to, re-installing the
                    operating system on the server) shall be performed by Codibu and shall incur an additional one-time
                    labor fee of $100 for each incident.
                    <br>
                    Codibu reserves the right to alter the dedicated server packages advertised on its website at its
                    discretion. Codibu is not required to upgrade Customers’ hardware or bandwidth allocation as a
                    result of a pricing or service package change. There will be an additional charge of $200 associated
                    with any such hardware upgrade requested by Customer. Customer will not be required to upgrade
                    hardware as a result of a pricing change.
                    <br>
                    For managed servers, Codibu is responsible for the security of the network, the kernel, and the base
                    operating system (defined as the standard set of debian packages that come installed with the
                    server). Codibu may take any steps it deems necessary at any time to protect the security of your
                    server (this generally includes applying security patches as well as upgrading the entire operating
                    system).
                    <br>
                    For unmanaged servers not using a “DreamCatcher” monitoring option, Customer is responsible for
                    keeping the security of their system up to date.
                    <br>
                    This includes but is not limited to the following requirements:
                    <br>
                    <span class="ms-4"><b>The kernel will be patched within 7 days of any announced security hole relating to the kernel</b></span>
                    <br>
                </p>
                <ol>
                    <li>Any security patches for all installed software must be applied within 7 days of their general
                        announcement to the security community at large.
                    </li>
                    <li>Codibu is not responsible for notifying unmanaged servers of the need to apply patches. Failure
                        to comply with these requirements is grounds for termination of contract without refund. Codibu
                        reserves the right to take any action upon unmanaged dedicated servers it deems necessary at any
                        time to protect the security and integrity of Codibu’s network.
                    </li>
                </ol>
                <h6 class="my-3">Age</h6>
                <p>The Customer certifies that they are at least 18 years of age, or that their parent or legal guardian
                    will act as the “customer” in terms of this contract.</p>
                <h6 class="my-3">Termination</h6>
                <p>
                    This contract may be terminated by either party, without cause, by giving the other party 14 days
                    written notice. Codibu will accept termination by electronic mail. Notwithstanding the above, Codibu
                    may terminate service under this contract at any time, without penalty, if the Customer fails to
                    comply with the terms of this contract, including non-payment. Codibu reserves the right to charge a
                    reinstatement fee.
                </p>
                <h6 class="my-3">Limited Liability</h6>
                <p>
                    Customer expressly agrees that use of Codibu’s Services is at Customer’s sole risk. Neither Codibu,
                    its employees, affiliates, agents, third party information providers, merchants licensers or the
                    like, warrant that Codibu’s Services will not be interrupted or error free; nor do they make any
                    warranty as to the results that may be obtained from the use of the Services or as to the accuracy,
                    reliability or content of any information service or merchandise contained in or provided through
                    Codibu Services, unless otherwise expressly stated in this contract.
                    <br>
                    Under no circumstances, including negligence, shall Codibu, its offices, agents or anyone else
                    involved in creating, producing or distributing Codibu’s Server service be liable for any direct,
                    indirect, incidental, special or consequential damages that result from the use of or inability to
                    use Codibu’s Services; or that results from mistakes, omissions, interruptions, deletion of files,
                    errors, defects, delays in operation, or transmission or any failure of performance, whether or not
                    limited to acts of God, communication failure, theft, destruction or unauthorized access to Codibu’s
                    records, programs or services. Customer hereby acknowledges that this paragraph shall apply to all
                    content utilizing Codibu’s Services.
                    <br>
                    Notwithstanding the above, Customer’s exclusive remedies and Codibu’s total liability for all
                    damages, losses and causes of actions whether in contract, tort including negligence or otherwise,
                    arising out of or in connection with these Terms or from the use or inability to use the Services,
                    shall not exceed the aggregate amount which Customer paid to Codibu during the six (6) months
                    immediately preceding the claim.
                    <br>
                </p>
                <h6 class="my-3">Promotional Codes</h6>
                <p>
                    By using a promotional (“promo”) code you waive the option to indicate who referred you to Codibu.
                    <br>
                    You may not change/submit a promo code or referrer after you’ve finished signing up.
                    <br>
                    Promo codes/referrers are for new customers only — if you use one you may not host on your account
                    any domain ever previously hosted with Codibu.
                </p>
                <h6 class="my-3">Indemnification</h6>
                <p>
                    Customer agrees that it shall defend, indemnify, save and hold Codibu harmless from any and all
                    demands, liabilities, losses, costs and claims, including reasonable attorneys’ fees,
                    (“Liabilities”) asserted against Codibu, its agents, its customers, servants officers and employees,
                    that may arise or result from any service provided or performed or agreed to be performed or any
                    product sold by Customer,its agents, employees or assigns.
                    <br>
                    Customer agrees to defend, indemnify and hold harmless Codibu against Liabilities arising out of
                    any injury to person or property caused by any products sold or otherwise distributed in connection
                    with Codibu’s Server;
                    any material supplied by Customer infringing or allegedly infringing on the proprietary rights of a
                    third party;
                    copyright infringement;
                    any defective product which Customer sold on Codibu’s servers.
                </p>
                <h6 class="my-3">Sanctioned Countries</h6>
                <p>
                    Customer agrees to comply with all applicable export and reexport control laws and regulations,
                    including the Export Administration Regulations (“EAR”) maintained by the U.S. Department of
                    Commerce, trade and economic sanctions maintained by the Treasury Department’s Office of Foreign
                    Assets Control, and the International Traffic in Arms Regulations (“ITAR”) maintained by the
                    Department of State. Specifically, Customer covenants that it shall not – directly or indirectly –
                    sell, export, re-export, transfer, divert, or otherwise dispose of any products, software, or
                    technology (including products derived from or based on such technology) received from Codibu under
                    this Agreement to any destination, entity, or person prohibited by the laws or regulations of the
                    United States, without obtaining prior authorization from the competent government authorities as
                    required by those laws and regulations. Customer agrees to indemnify, to the fullest extent
                    permitted by law, Codibu from and against any fines or penalties that may arise as a result of
                    Customer’s breach of this provision. This export control clause shall survive termination or
                    cancellation of this Agreement.
                </p>
                <h6 class="my-3">Other Terms and Policies</h6>
                <p>
                    Customer agrees to abide by the terms set forth in this Terms of Service as well as other Codibu
                    policy documents including, but not limited to the following, each of which are incorporated herein
                    by reference, and together with the Terms of Service, constitute the entire agreement of the
                    parties:
                </p>
                <ol>
                    <li>Acceptable Use Policy</li>
                    <li>Anti-Spam Policy</li>
                    <li>Unlimited Policy</li>
                    <li>Privacy Policy</li>
                    <li>Customer EU Data Processing Addendum</li>
                    <li>Professional Services Terms</li>
                    <li>Domain Registration Terms</li>
                    <li>A full list of all policies can be found here: <a href="">www.codibu.com/legal</a>.</li>
                </ol>
                <p>Customer also agrees to abide by all applicable terms set forth by all Codibu partners and
                    subsidiaries.</p>
                <h6 class="my-3">Partner Product Terms</h6>
                <p>
                    BoldGrid: By utilizing WP Website Builder, you agree to BoldGrid’s Terms [here].
                </p>
                <p>
                    G Suite and Google Workspace: By utilizing Codibu’s partnered Google Workspace (formerly known as G
                    Suite) service, you agree to the Google Workspace Terms of Service.
                </p>
                <h6 class="my-3">Contract Revisions</h6>
                <p>
                    As Codibu evolves, we may modify these Terms from time to time to reflect changes in our business.
                    These modifications may include cancellation of outdated products or Services, additional fees, and
                    changes to our policies among others. In some instances, we may provide you with additional notice
                    of updates including but not limited to adding a statement to the website, via the newsletter, or
                    sending you an email notification. However, it is your responsibility to periodically check for the
                    current version of our Terms by visiting this page (and other pages referenced in the Terms).
                </p>
                <p>
                    If you continue to use or access the Services after the effective date set forth above, you agree to
                    be bound by any revised Terms, and all other terms or policies incorporated herein either directly
                    or by reference.
                </p>
                <h6 class="my-3">Transfer</h6>
                <p>
                    Customer may not assign or transfer Customer’s rights or obligations under these Terms without the
                    written consent of Codibu.
                </p>
                <h6 class="my-3">Governing Law</h6>
                <p>The Terms and the resolution of any disputes shall be governed by and construed in accordance with
                    the laws of the State of California, without regard to its conflict of laws principles.</p>
            </div>
            <!--            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>-->
        </div>
    </div>
</div>
@endsection

@push('js')

@endpush
