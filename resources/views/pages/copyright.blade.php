@extends('layouts.main')

@section('title', 'Copyright and DMCA')

@section('content')
    <div class="landing">

        <section class="content">
            <h1 class="section-heading">Copyright and DMCA</h1>

            <p>We believe that all images on this website are available in the public domain. If you are a copyright
                owner
                of one or more images posted on this website, the below sections will be of particular importance to
                you.</p>

            <h4>Notice to Owners of Copyrighted Works</h4>

            <p>If you are a copyright owner, authorized to act on behalf of one, or authorized to act under any
                exclusive right under copyright,
                please report alleged copyright infringements taking place on or
                through {{ Config::get('custom.app-name') }} (http://www.{{ Config::get('custom.app-domain') }})
                by
                completing the following notice ("Notice") and delivering it via email
                to {{ Config::get('custom.email-address-admin') }} </p>

            <h4>DMCA Notice of Alleged Infringement</h4>

            <p>A proper DMCA Notice will notify {{ Config::get('custom.website-name') }} of particular facts in a
                document signed under penalty of perjury and delivered
                via email to contact [at] {{ Config::get('custom.primary-domain') }}<br>
                To write a proper Notice, you must provide the following information, which list comes directly from the
                DMCA statute:<br>
                A physical or electronic signature of a person authorized to act on behalf of the owner of an exclusive
                right that is allegedly infringed.
            <ul>
                <li>Identification of the copyrighted work claimed to have been infringed, or, if multiple
                    copyrighted works at a single online site are covered by a single Notification, a representative
                    list of such works at that site.
                </li>
                <li>Identification of the material that is claimed to be infringing or to be the subject of
                    infringing activity and that is to be removed or access to which is to be disabled, and
                    information reasonably sufficient to permit the service provider to locate the material.
                </li>
                <li>Information reasonably sufficient to permit the service provider to contact the complaining
                    party, such as an address, telephone number, and, if available, an electronic mail address at
                    which the complaining party may be contacted.
                </li>
                <li>A statement that the complaining party has a good faith belief that the use of the material in
                    the manner complained of is not authorized by the copyright owner, its agent or the law.
                </li>
                <li>A statement that the information in the Notification is accurate, and under penalty of perjury,
                    that the complaining party is authorized to act on behalf of the owner of the exclusive right
                    that is allegedly infringed.
                </li>
            </ul>
            </p>
            <p>
                Upon receipt of a valid claim, we will follow the procedures provided in the DMCA
                which prescribe a notice and take down procedure, subject to the user's or webmaster's
                right to submit a Counter-Notification claiming lawful use of the disabled works.
                We will have the disputed material removed from public view.
                We will also notify the user or webmaster (where possible) who posted the allegedly
                infringing material that has been removed.
            </p>
        </section>
    </div>
@endsection