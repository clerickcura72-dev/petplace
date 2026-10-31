<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pet Owner Dashboard | Pet Place</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: #f7f6fb;
            color: #242033;
            font-family: "DM Sans", "Segoe UI", sans-serif;
        }
        a { color: inherit; text-decoration: none; }
        button, input, select, textarea { font: inherit; }
        .topbar {
            height: 72px;
            padding: 0 max(24px, calc((100% - 1120px) / 2));
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border-bottom: 1px solid #ece9f3;
        }
        .brand { color: #6c4fc7; font-size: 21px; font-weight: 800; letter-spacing: -.5px; }
        .brand span { color: #272238; }
        .topbar-right { display: flex; align-items: center; gap: 20px; color: #696579; font-size: 14px; }
        .logout {
            border: 1px solid #e3deef;
            border-radius: 10px;
            background: #fff;
            color: #5e4aa1;
            padding: 9px 14px;
            cursor: pointer;
            font-weight: 700;
        }
        .page { width: min(1320px, calc(100% - 40px)); margin: 42px auto 64px; }
        .eyebrow { color: #7962ba; font-size: 12px; font-weight: 800; letter-spacing: 1.4px; text-transform: uppercase; }
        h1 { margin: 8px 0 7px; font-size: clamp(27px, 4vw, 36px); letter-spacing: -1.1px; }
        .intro { margin: 0; color: #777386; font-size: 15px; }
        .grid { display: grid; grid-template-columns: minmax(0, 2.2fr) minmax(320px, .8fr); gap: 22px; margin-top: 28px; align-items: start; }
        .card { background: #fff; border: 1px solid #eeebf4; border-radius: 18px; box-shadow: 0 8px 28px rgba(44, 32, 82, .045); }
        .pet-card { overflow: hidden; }
        .pet-content { padding: 26px 26px 25px; }
        .pet-heading { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(250px, .7fr); align-items: center; gap: 28px; }
        .pet-identity { display: grid; grid-template-columns: 112px minmax(0, 1fr) auto; min-width: 0; align-items: center; gap: 16px; }
        .pet-photo {
            width: 112px; height: 112px; flex: 0 0 112px;
            display: grid; place-items: center; overflow: hidden;
            border: 5px solid #fff; border-radius: 25px; background: #edf4f0;
            color: #557765; font-size: 53px;
        }
        .pet-photo img { width: 100%; height: 100%; object-fit: cover; }
        .pet-title { flex: 1; min-width: 0; padding-bottom: 5px; }
        .pet-title h2 { margin: 0 0 5px; font-size: 25px; line-height: 1.2; letter-spacing: -.5px; overflow-wrap: break-word; }
        .pet-kind { color: #777386; font-size: 14px; }
        .upload-form { display: flex; justify-content: flex-end; margin: 0; }
        .upload-label {
            display: inline-flex; align-items: center; gap: 7px; padding: 10px 13px;
            border: 1px solid #e3dcf4; border-radius: 10px; color: #624bb0;
            cursor: pointer; font-size: 13px; font-weight: 700;
        }
        .upload-label:hover, .edit-summary:hover { background: #f8f6ff; }
        .upload-input { position: absolute; width: 1px; height: 1px; overflow: hidden; clip: rect(0,0,0,0); }
        .pet-details { display: grid; grid-template-columns: 1fr 1fr; gap: 13px; margin-top: 24px; }
        .pet-switcher { display: flex; min-width: 0; flex-wrap: wrap; align-items: end; gap: 10px; padding: 0; }
        .pet-switcher .field { flex: 1; min-width: 170px; margin: 0; }
        .pet-switcher .btn, .add-pet-form .btn { white-space: nowrap; }
        .add-pet-section {
            display: flex; align-items: flex-start; justify-content: space-between; gap: 28px;
            margin-top: 24px; padding: 25px 28px; border: 1px solid #e8e3f1;
            border-radius: 18px; background: #fff; box-shadow: 0 8px 28px rgba(44, 32, 82, .045);
        }
        .add-pet-copy { display: flex; align-items: flex-start; gap: 14px; }
        .add-pet-icon { display: grid; place-items: center; width: 44px; height: 44px; flex: 0 0 44px; border-radius: 13px; background: #f0ebff; color: #6c4fc7; font-size: 23px; font-weight: 700; }
        .add-pet-copy h2 { margin: 1px 0 6px; font-size: 18px; }
        .add-pet-copy p { margin: 0; color: #898597; font-size: 13px; line-height: 1.5; }
        .add-pet-details { flex: 0 0 auto; }
        .add-pet-details summary {
            display: inline-flex; align-items: center; justify-content: center; gap: 7px;
            min-height: 42px; padding: 0 16px; border: 1px solid #6c4fc7; border-radius: 10px;
            background: #6c4fc7; color: #fff; cursor: pointer; font-size: 13px; font-weight: 800;
            list-style: none; transition: background .15s, transform .15s;
        }
        .add-pet-details summary:hover { background: #5940ad; transform: translateY(-1px); }
        .add-pet-details[open] summary { margin-bottom: 4px; }
        .add-pet-details summary::-webkit-details-marker { display: none; }
        .add-pet-form { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-top: 14px; }
        .add-pet-form .field { margin: 0; }
        .add-pet-form .btn { grid-column: 1 / -1; justify-self: start; min-width: 130px; }
        .add-pet-details[open] { flex: 1; }
        .add-pet-details[open] .add-pet-form { max-width: 680px; }
        .detail { min-height: 83px; padding: 15px 16px; border: 1px solid #efedf4; border-radius: 12px; background: #fcfbfe; }
        .detail-label { color: #898597; font-size: 11px; font-weight: 800; letter-spacing: .8px; text-transform: uppercase; }
        .detail-value { margin-top: 8px; font-size: 15px; font-weight: 700; }
        .detail-value.empty { color: #aaa6b5; font-weight: 500; }
        details.edit-details { margin-top: 15px; }
        .edit-summary { display: inline-flex; align-items: center; gap: 7px; color: #6c4fc7; cursor: pointer; font-size: 13px; font-weight: 700; list-style: none; }
        .edit-summary::-webkit-details-marker { display: none; }
        .edit-form { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; padding: 15px; margin-top: 12px; border-radius: 12px; background: #f8f6fc; }
        .edit-form label, .field label { display: block; margin-bottom: 6px; color: #696579; font-size: 12px; font-weight: 700; }
        .edit-form input, .field input, .field select, .field textarea {
            width: 100%; border: 1px solid #dfdbe8; border-radius: 9px; background: #fff; padding: 10px 11px; color: #242033;
        }
        .edit-form input { width: auto; }
        .btn { display: inline-flex; justify-content: center; align-items: center; border: 0; border-radius: 10px; background: #6c4fc7; color: #fff; cursor: pointer; padding: 11px 16px; font-weight: 700; }
        .btn:hover { background: #5940ad; }
        .btn-full { width: 100%; }
        .side-stack { display: grid; gap: 22px; }
        .section-card { padding: 23px; }
        .section-heading { display: flex; align-items: center; gap: 11px; margin-bottom: 18px; }
        .section-icon { width: 39px; height: 39px; display: grid; place-items: center; border-radius: 12px; background: #f0ebff; color: #6c4fc7; font-size: 19px; }
        .section-heading h2 { margin: 0; font-size: 18px; letter-spacing: -.3px; }
        .section-heading p { margin: 4px 0 0; color: #898597; font-size: 12px; }
        .field { margin-bottom: 13px; }
        .field textarea { min-height: 74px; resize: vertical; }
        .vaccine-fieldset { min-width: 0; margin: 0 0 15px; padding: 14px; border: 1px solid #e8e3f1; border-radius: 12px; }
        .vaccine-fieldset legend { padding: 0 5px; color: #4b455b; font-size: 13px; font-weight: 800; }
        .vaccine-note { margin: 0 0 12px; color: #898597; font-size: 11px; line-height: 1.5; }
        .vaccine-list { display: grid; gap: 8px; }
        .vaccine-option { display: flex; align-items: flex-start; gap: 10px; padding: 10px; border: 1px solid #efedf4; border-radius: 10px; cursor: pointer; }
        .vaccine-option:hover { border-color: #cfc3ed; background: #fcfbff; }
        .vaccine-option input { width: 19px; height: 19px; flex: 0 0 19px; margin: 1px 0 0; appearance: none; border: 2px solid #c7c1d2; border-radius: 50%; background: #fff; cursor: pointer; }
        .vaccine-option input:checked { border-color: #6c4fc7; background-color: #6c4fc7; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath d='m3 8 3 3 7-7' fill='none' stroke='white' stroke-width='2'/%3E%3C/svg%3E"); background-position: center; background-repeat: no-repeat; background-size: 13px; }
        .vaccine-option input:disabled { opacity: 1; cursor: not-allowed; }
        .vaccine-option input:focus-visible { outline: 3px solid #cfc3ed; outline-offset: 2px; }
        .vaccine-copy { display: grid; gap: 3px; }
        .vaccine-name { color: #3d3948; font-size: 12px; font-weight: 700; }
        .vaccine-description { color: #898597; font-size: 11px; line-height: 1.45; }
        .vaccine-suboptions { display: grid; gap: 7px; margin: 0 0 0 19px; padding-left: 10px; border-left: 2px solid #eee9f8; }
        .grooming-fields { margin: 0 0 15px; padding: 14px; border: 1px solid #e8e3f1; border-radius: 12px; }
        .grooming-fields legend { padding: 0 5px; color: #4b455b; font-size: 13px; font-weight: 800; }
        .grooming-addons { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; margin-bottom: 15px; }
        .grooming-addon { display: flex; align-items: flex-start; gap: 8px; padding: 9px; border: 1px solid #efedf4; border-radius: 9px; color: #4b455b; font-size: 12px; cursor: pointer; }
        .grooming-addon input { margin: 2px 0 0; accent-color: #6c4fc7; }
        .grooming-preferences { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 12px; }
        .grooming-preferences .field { min-width: 0; }
        .grooming-preferences .grooming-full-width { grid-column: 1 / -1; }
        .grooming-vaccines { display: grid; gap: 7px; padding: 12px; margin-bottom: 14px; border-radius: 10px; background: #fcfbfe; }
        .grooming-vaccine-status { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 8px; color: #4b455b; font-size: 12px; }
        .grooming-vaccine-status .vaccination-status { justify-self: auto; }
        .help { margin: -4px 0 15px; color: #898597; font-size: 12px; line-height: 1.5; }
        .appointment-list { display: grid; gap: 12px; }
        .appointment-item { padding: 13px 14px; border: 1px solid #efedf4; border-radius: 12px; }
        .appointment-row { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
        .appointment-date { font-size: 14px; font-weight: 700; }
        .badge { padding: 5px 8px; border-radius: 20px; background: #fff5dc; color: #9a6b0b; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .appointment-vet, .appointment-reason { margin-top: 7px; color: #777386; font-size: 12px; }
        .empty-state { color: #898597; font-size: 13px; line-height: 1.6; }
        .vaccination-card { margin-top: 22px; padding: 25px; }
        .vaccination-summary { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 18px; padding: 17px; margin-bottom: 20px; border: 1px solid #efedf4; border-radius: 13px; background: #fcfbfe; }
        .vaccination-summary-copy { display: grid; gap: 6px; }
        .vaccination-summary-copy strong { font-size: 16px; }
        .vaccination-summary-copy span { color: #777386; font-size: 13px; }
        .vaccination-list { display: grid; gap: 12px; }
        .vaccination-item { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 8px 18px; padding: 16px; border: 1px solid #efedf4; border-radius: 12px; }
        .vaccination-item-heading { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; }
        .vaccination-item-heading strong { font-size: 14px; }
        .vaccination-category { padding: 4px 8px; border-radius: 20px; background: #f0ebff; color: #5e4aa1; font-size: 10px; font-weight: 800; }
        .vaccination-status { justify-self: end; align-self: start; padding: 5px 9px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .vaccination-status.up_to_date { background: #e6f7ec; color: #247342; }
        .vaccination-status.due_soon { background: #fff3d4; color: #8b6500; }
        .vaccination-status.overdue { background: #fde8e8; color: #a52c2c; }
        .vaccination-status.not_yet_given { background: #f0eff3; color: #777386; }
        .vaccination-details { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 10px 14px; grid-column: 1 / -1; }
        .vaccination-detail { min-width: 0; color: #777386; font-size: 12px; line-height: 1.5; overflow-wrap: anywhere; }
        .vaccination-detail strong { display: block; margin-bottom: 2px; color: #696579; font-size: 10px; letter-spacing: .4px; text-transform: uppercase; }
        .vaccination-notes { grid-column: 1 / -1; margin: 0; color: #777386; font-size: 12px; line-height: 1.5; }
        .vaccination-request-link { grid-column: 1 / -1; justify-self: start; color: #624bb0; font-size: 12px; font-weight: 800; }
        .vaccination-request-link:hover { text-decoration: underline; }
        .alert { padding: 12px 15px; margin: 18px 0 0; border: 1px solid #bee8d0; border-radius: 10px; background: #f0fff6; color: #276843; font-size: 13px; }
        .errors { padding: 12px 15px; margin: 18px 0 0; border: 1px solid #ffd1d1; border-radius: 10px; background: #fff6f6; color: #a83232; font-size: 13px; }
        .errors ul { margin: 5px 0 0; padding-left: 18px; }
        @media (max-width: 980px) {
            .grid { grid-template-columns: 1fr; }
            .page { margin-top: 30px; }
        }
        @media (max-width: 760px) {
            .pet-heading { grid-template-columns: 1fr; gap: 20px; }
            .pet-switcher { width: 100%; }
        }
        @media (max-width: 520px) {
            .topbar { height: 62px; padding: 0 17px; }
            .topbar-right { gap: 10px; font-size: 12px; }
            .page { width: calc(100% - 28px); }
            .pet-content { padding: 20px 18px; }
            .pet-identity { grid-template-columns: 80px minmax(0, 1fr); gap: 12px; }
            .pet-photo { width: 80px; height: 80px; flex-basis: 80px; border-radius: 20px; font-size: 40px; }
            .pet-title h2 { font-size: 22px; }
            .upload-form { grid-column: 2; justify-content: flex-start; }
            .pet-details { grid-template-columns: 1fr; }
            .section-card { padding: 19px; }
            .vaccination-card { padding: 19px; }
            .vaccination-details { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .grooming-addons, .grooming-preferences { grid-template-columns: 1fr; }
            .add-pet-section { display: grid; gap: 18px; padding: 20px; }
            .add-pet-details { width: 100%; }
            .add-pet-form { grid-template-columns: 1fr; }
            .add-pet-form .btn { grid-column: auto; width: 100%; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ route('owner.dashboard') }}">Pet <span>Place.</span></a>
        <div class="topbar-right">
            <span>Hi, {{ $petOwner->first_name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout" type="submit">Log out</button>
            </form>
        </div>
    </header>

    <main class="page">
        <h1>Welcome back, {{ $petOwner->first_name }}!</h1>
        <p class="intro">Keep your pet's details close and request a visit whenever they need care.</p>

        @if (session('status'))
            <div class="alert" role="status">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="errors" role="alert">
                Please check the information below:
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid">
            <section class="card pet-card" aria-labelledby="pet-profile-title">
                <div class="pet-content">
                <div class="pet-heading">
                    <div class="pet-identity">
                        <div class="pet-photo">
                            @if ($pet->pet_photo_path)
                                <img src="{{ asset('storage/'.$pet->pet_photo_path) }}" alt="Photo of {{ $pet->pet_name ?: 'your pet' }}">
                            @else
                                {{ strtolower($pet->pet_type ?? '') === 'cat' ? '🐱' : (strtolower($pet->pet_type ?? '') === 'dog' ? '🐶' : '🐾') }}
                            @endif
                        </div>
                        <div class="pet-title">
                            <h2 id="pet-profile-title">{{ $pet->pet_name ?: 'Your pet' }}</h2>
                            <div class="pet-kind">{{ $pet->pet_type ? ucfirst($pet->pet_type) : 'Pet type not added yet' }}</div>
                        </div>
                        <form class="upload-form" method="POST" action="{{ route('owner.pet-profile.update', $pet) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <label class="upload-label" for="pet_photo">＋ {{ $pet->pet_photo_path ? 'Change photo' : 'Add a photo' }}</label>
                            <input class="upload-input" id="pet_photo" name="pet_photo" type="file" accept="image/jpeg,image/png,image/webp" onchange="this.form.submit()">
                        </form>
                    </div>
                    <form class="pet-switcher" method="GET" action="{{ route('owner.dashboard') }}">
                        <div class="field">
                            <label for="pet_id">Your pets</label>
                            <select id="pet_id" name="pet_id" onchange="this.form.submit()">
                                @foreach ($pets as $ownerPet)
                                    <option value="{{ $ownerPet->id }}" {{ $pet->id === $ownerPet->id ? 'selected' : '' }}>
                                        {{ $ownerPet->pet_name ?: 'Pet profile '.$loop->iteration }}{{ $ownerPet->pet_type ? ' · '.ucfirst($ownerPet->pet_type) : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn" type="submit">View pet</button>
                    </form>
                </div>

                    <div class="pet-details">
                        <div class="detail">
                            <div class="detail-label">Pet name</div>
                            <div class="detail-value {{ $pet->pet_name ? '' : 'empty' }}">{{ $pet->pet_name ?: 'Not provided' }}</div>
                        </div>
                        <div class="detail">
                            <div class="detail-label">Pet type</div>
                            <div class="detail-value {{ $pet->pet_type ? '' : 'empty' }}">{{ $pet->pet_type ? ucfirst($pet->pet_type) : 'Not provided' }}</div>
                        </div>
                        <div class="detail">
                            <div class="detail-label">Birthdate / age</div>
                            @if ($pet->pet_birthdate)
                                @php($petAge = $pet->pet_birthdate->diff(now()))
                                @if ($petAge->y > 0)
                                    @php($ageValue = $petAge->y)
                                    @php($ageUnit = $ageValue === 1 ? 'year' : 'years')
                                @elseif ($petAge->m > 0)
                                    @php($ageValue = $petAge->m)
                                    @php($ageUnit = $ageValue === 1 ? 'month' : 'months')
                                @else
                                    @php($ageValue = $petAge->d)
                                    @php($ageUnit = $ageValue === 1 ? 'day' : 'days')
                                @endif
                                <div class="detail-value">{{ $pet->pet_birthdate->format('F j, Y') }} <span style="color:#898597;font-size:12px;font-weight:500;">({{ $ageValue }} {{ $ageUnit }} old)</span></div>
                            @else
                                <div class="detail-value empty">Not added yet</div>
                            @endif
                        </div>
                    </div>

                    <details class="edit-details">
                        <summary class="edit-summary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                            {{ $pet->pet_birthdate ? 'Edit birthdate' : 'Add birthdate' }}
                        </summary>
                        <form class="edit-form" method="POST" action="{{ route('owner.pet-profile.update', $pet) }}">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label for="pet_birthdate">Pet's birthdate</label>
                                <input id="pet_birthdate" name="pet_birthdate" type="date" max="{{ now()->toDateString() }}" value="{{ old('pet_birthdate', optional($pet->pet_birthdate)->format('Y-m-d')) }}">
                            </div>
                            <button class="btn" type="submit">Save date</button>
                        </form>
                    </details>
                </div>
            </section>

            <div class="side-stack">
                <section class="card section-card" id="request-appointment">
                    <div class="section-heading">
                        <div class="section-icon" aria-hidden="true">＋</div>
                        <div>
                            <h2>Request an appointment</h2>
                            <p>Choose a service and preferred time for {{ $pet->pet_name ?: 'your pet' }}.</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('owner.appointments.store') }}">
                        @csrf
                        <input type="hidden" name="pet_id" value="{{ $pet->id }}">
                        <div class="field">
                            <label for="service_type">Service type</label>
                            <select id="service_type" name="service_type" required>
                                <option value="Check-up / Consultation" {{ old('service_type', request('service_type', 'Check-up / Consultation')) === 'Check-up / Consultation' ? 'selected' : '' }}>Check-up / Consultation</option>
                                <option value="Vaccination" {{ old('service_type', request('service_type')) === 'Vaccination' ? 'selected' : '' }}>Vaccination</option>
                                <option value="Grooming" {{ old('service_type', request('service_type')) === 'Grooming' ? 'selected' : '' }}>Grooming</option>
                            </select>
                        </div>
                        <div class="field" id="vet-field">
                            <label for="vet_id">Veterinarian</label>
                            <select id="vet_id" name="vet_id">
                                <option value="">Any available veterinarian</option>
                                @foreach ($vets as $vet)
                                    <option value="{{ $vet->id }}" {{ old('vet_id') == $vet->id ? 'selected' : '' }}>
                                        Dr. {{ $vet->first_name }} {{ $vet->last_name }}{{ $vet->specialty ? ' · '.ucfirst($vet->specialty).' specialist' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field" id="groomer-field" hidden>
                            <label for="groomer_id">Groomer</label>
                            <select id="groomer_id" name="groomer_id" disabled>
                                <option value="">Any available groomer</option>
                                @foreach ($groomers as $groomer)
                                    <option value="{{ $groomer->id }}" {{ old('groomer_id') == $groomer->id ? 'selected' : '' }}>
                                        {{ $groomer->first_name }} {{ $groomer->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <fieldset class="grooming-fields" id="grooming-fields" hidden disabled>
                            <legend>Grooming options</legend>
                            <div class="field">
                                <label>Add-ons</label>
                                <div class="grooming-addons">
                                    @foreach ([
                                        'nail_trimming' => 'Nail trimming',
                                        'ear_cleaning' => 'Ear cleaning',
                                        'teeth_brushing' => 'Teeth brushing',
                                        'anal_gland_expression' => 'Anal gland expression',
                                        'flea_tick_treatment' => 'Flea & tick treatment',
                                        'de_shedding' => 'De-shedding treatment',
                                        'medicated_shampoo' => 'Medicated or special shampoo (sensitive skin)',
                                        'perfume_bow_bandana' => 'Perfume / bow / bandana',
                                    ] as $addon => $addonLabel)
                                        <label class="grooming-addon">
                                            <input type="checkbox" name="grooming_addons[]" value="{{ $addon }}" {{ in_array($addon, old('grooming_addons', []), true) ? 'checked' : '' }}>
                                            <span>{{ $addonLabel }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grooming-preferences">
                                <div class="field">
                                    <label for="haircut_style">Haircut style or length</label>
                                    <select id="haircut_style" name="haircut_style">
                                        <option value="">No preference</option>
                                        @foreach (['Short', 'Medium', 'Long', 'Same as last time', 'Let the groomer decide'] as $haircutStyle)
                                            <option value="{{ $haircutStyle }}" {{ old('haircut_style') === $haircutStyle ? 'selected' : '' }}>{{ $haircutStyle }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="shampoo_type">Shampoo type</label>
                                    <select id="shampoo_type" name="shampoo_type">
                                        <option value="">No preference</option>
                                        @foreach (['Regular', 'Hypoallergenic', 'Medicated'] as $shampooType)
                                            <option value="{{ $shampooType }}" {{ old('shampoo_type') === $shampooType ? 'selected' : '' }}>{{ $shampooType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="fur_matted">Fur condition</label>
                                    <select id="fur_matted" name="fur_matted">
                                        <option value="">Not specified</option>
                                        <option value="0" {{ old('fur_matted') === '0' ? 'selected' : '' }}>Not matted or tangled</option>
                                        <option value="1" {{ old('fur_matted') === '1' ? 'selected' : '' }}>Matted or tangled</option>
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="pet_size">Pet size or weight range</label>
                                    <select id="pet_size" name="pet_size">
                                        <option value="">Not specified</option>
                                        @foreach (['Small', 'Medium', 'Large', 'Giant'] as $petSize)
                                            <option value="{{ $petSize }}" {{ old('pet_size') === $petSize ? 'selected' : '' }}>{{ $petSize }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="coat_type">Coat type</label>
                                    <select id="coat_type" name="coat_type">
                                        <option value="">Not specified</option>
                                        @foreach (['Short', 'Long', 'Curly', 'Double coat'] as $coatType)
                                            <option value="{{ $coatType }}" {{ old('coat_type') === $coatType ? 'selected' : '' }}>{{ $coatType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field">
                                    <label for="temperament">Temperament around strangers</label>
                                    <select id="temperament" name="temperament">
                                        <option value="">Not specified</option>
                                        @foreach (['Fine around strangers', 'Nervous', 'Aggressive'] as $temperament)
                                            <option value="{{ $temperament }}" {{ old('temperament') === $temperament ? 'selected' : '' }}>{{ $temperament }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="field grooming-full-width">
                                    <label for="allergies_or_skin_problems">Allergies or skin problems</label>
                                    <textarea id="allergies_or_skin_problems" name="allergies_or_skin_problems" maxlength="1000" placeholder="List any allergies or skin sensitivities">{{ old('allergies_or_skin_problems') }}</textarea>
                                </div>
                            </div>

                            <div class="field">
                                <label>Vaccination status from the pet's records</label>
                                <div class="grooming-vaccines">
                                    @foreach ($groomingVaccines as $vaccineName => $vaccineStatus)
                                        <div class="grooming-vaccine-status">
                                            <span>{{ ucfirst($vaccineName) }}{{ $vaccineStatus['record']?->next_due_date ? ' · due '.$vaccineStatus['record']->next_due_date->format('M j, Y') : '' }}</span>
                                            <span class="vaccination-status {{ $vaccineStatus['status'] }}">
                                                {{ ['up_to_date' => 'Up to date', 'due_soon' => 'Due soon', 'overdue' => 'Overdue', 'not_yet_given' => 'Not recorded'][$vaccineStatus['status']] }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                                <p class="help">Groomers may require current rabies vaccination and, for dogs, may request Bordetella records. Please confirm their requirements.</p>
                            </div>
                        </fieldset>
                        @if ($vaccineOptions)
                            @php($selectedVaccines = old('vaccines', request()->filled('vaccine') ? [request('vaccine')] : []))
                            <fieldset class="vaccine-fieldset" id="vaccines-field" hidden disabled>
                                <legend>{{ ucfirst($pet->pet_type) }} vaccines</legend>
                                <p class="vaccine-note">Select all vaccines you'd like to discuss for your pet. Your veterinarian will confirm which are appropriate and when they are due.</p>
                                <div class="vaccine-list">
                                    @if (strtolower($pet->pet_type) === 'cat')
                                        <label class="vaccine-option" for="vaccine-cat_fvrcp">
                                            <input id="vaccine-cat_fvrcp" type="checkbox" name="vaccines[]" value="cat_fvrcp" {{ in_array('cat_fvrcp', $selectedVaccines, true) ? 'checked' : '' }}>
                                            <span class="vaccine-copy">
                                                <span class="vaccine-name">{{ $vaccineOptions['cat_fvrcp'][0] }}</span>
                                                <span class="vaccine-description">{{ $vaccineOptions['cat_fvrcp'][1] }}</span>
                                            </span>
                                        </label>
                                        <div class="vaccine-suboptions" id="fvrcp-components">
                                            @foreach (['cat_rhinotracheitis', 'cat_calicivirus', 'cat_panleukopenia'] as $vaccineId)
                                                <label class="vaccine-option" for="vaccine-{{ $vaccineId }}">
                                                    <input id="vaccine-{{ $vaccineId }}" type="checkbox" name="vaccines[]" value="{{ $vaccineId }}" {{ in_array($vaccineId, $selectedVaccines, true) ? 'checked' : '' }}>
                                                    <span class="vaccine-copy">
                                                        <span class="vaccine-name">{{ $vaccineOptions[$vaccineId][0] }}</span>
                                                        <span class="vaccine-description">{{ $vaccineOptions[$vaccineId][1] }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @foreach ($vaccineOptions as $vaccineId => $vaccine)
                                            @if (! in_array($vaccineId, ['cat_fvrcp', 'cat_rhinotracheitis', 'cat_calicivirus', 'cat_panleukopenia'], true))
                                                <label class="vaccine-option" for="vaccine-{{ $vaccineId }}">
                                                    <input id="vaccine-{{ $vaccineId }}" type="checkbox" name="vaccines[]" value="{{ $vaccineId }}" {{ in_array($vaccineId, $selectedVaccines, true) ? 'checked' : '' }}>
                                                    <span class="vaccine-copy">
                                                        <span class="vaccine-name">{{ $vaccine[0] }}</span>
                                                        <span class="vaccine-description">{{ $vaccine[1] }}</span>
                                                    </span>
                                                </label>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($vaccineOptions as $vaccineId => $vaccine)
                                            <label class="vaccine-option" for="vaccine-{{ $vaccineId }}">
                                                <input id="vaccine-{{ $vaccineId }}" type="checkbox" name="vaccines[]" value="{{ $vaccineId }}" {{ in_array($vaccineId, $selectedVaccines, true) ? 'checked' : '' }}>
                                                <span class="vaccine-copy">
                                                    <span class="vaccine-name">{{ $vaccine[0] }}</span>
                                                    <span class="vaccine-description">{{ $vaccine[1] }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    @endif
                                </div>
                            </fieldset>
                        @endif
                        <div class="field">
                            <label for="appointment_at">Preferred date and time</label>
                            <input id="appointment_at" name="appointment_at" type="datetime-local" min="{{ now()->addHour()->format('Y-m-d\TH:i') }}" value="{{ old('appointment_at') }}" required>
                        </div>
                        <div class="field">
                            <label for="reason">What does your pet need? <span style="font-weight:400;">(optional)</span></label>
                            <textarea id="reason" name="reason" maxlength="1000" placeholder="Anything you'd like the vet or groomer to know...">{{ old('reason') }}</textarea>
                        </div>
                        @if ($vets->isEmpty() && $groomers->isEmpty())
                            <p class="help">No staff are listed yet. You can still request service from any available staff member.</p>
                        @endif
                        <button class="btn btn-full" type="submit">Request appointment</button>
                    </form>
                </section>

                <section class="card section-card">
                    <div class="section-heading">
                        <div class="section-icon" aria-hidden="true">♡</div>
                        <div>
                            <h2>Appointment requests</h2>
                            <p>Keep track of your upcoming visits.</p>
                        </div>
                    </div>
                    @if ($appointments->isEmpty())
                        <p class="empty-state">You don't have any appointment requests yet. Use the form above to get started.</p>
                    @else
                        <div class="appointment-list">
                            @foreach ($appointments as $appointment)
                                <article class="appointment-item">
                                    <div class="appointment-row">
                                        <div class="appointment-date">{{ $appointment->appointment_at->format('M j, Y · g:i A') }}</div>
                                        <span class="badge">{{ ucfirst($appointment->status) }}</span>
                                    </div>
                                    <div class="appointment-vet">{{ $appointment->service_type }}</div>
                                    @if ($appointment->pet)
                                        <div class="appointment-vet">For {{ $appointment->pet->pet_name ?: 'your pet' }}</div>
                                    @endif
                                    <div class="appointment-vet">
                                        @if ($appointment->service_type === 'Grooming')
                                            {{ $appointment->groomer ? $appointment->groomer->first_name.' '.$appointment->groomer->last_name : 'Any available groomer' }}
                                        @else
                                            {{ $appointment->vet ? 'Dr. '.$appointment->vet->first_name.' '.$appointment->vet->last_name : 'Any available veterinarian' }}
                                        @endif
                                    </div>
                                    @if ($appointment->reason)
                                        <div class="appointment-reason">{{ $appointment->reason }}</div>
                                    @endif
                                    @if ($appointment->vaccines)
                                        <div class="appointment-reason">
                                            Vaccines requested: {{ collect($appointment->vaccines)->map(fn ($vaccine) => $vaccineLabels[$vaccine] ?? $vaccine)->join(', ') }}
                                        </div>
                                    @endif
                                    @if ($appointment->service_type === 'Grooming')
                                        @if ($appointment->grooming_addons)
                                            <div class="appointment-reason">Grooming add-ons: {{ collect($appointment->grooming_addons)->map(fn ($addon) => ucwords(str_replace('_', ' ', $addon)))->join(', ') }}</div>
                                        @endif
                                        @php($groomingPreferences = array_filter([
                                            'Haircut' => $appointment->haircut_style,
                                            'Shampoo' => $appointment->shampoo_type,
                                            'Fur matted' => is_null($appointment->fur_matted) ? null : ($appointment->fur_matted ? 'Yes' : 'No'),
                                            'Size' => $appointment->pet_size,
                                            'Coat' => $appointment->coat_type,
                                            'Temperament' => $appointment->temperament,
                                            'Allergies / skin problems' => $appointment->allergies_or_skin_problems,
                                        ]))
                                        @if ($groomingPreferences)
                                            <div class="appointment-reason">
                                                {{ collect($groomingPreferences)->map(fn ($value, $label) => $label.': '.$value)->join(' · ') }}
                                            </div>
                                        @endif
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        </div>

        @if ($vaccineOptions)
            <section class="card vaccination-card" aria-labelledby="vaccination-records-heading">
                <div class="section-heading">
                    <div class="section-icon" aria-hidden="true">＋</div>
                    <div>
                        <h2 id="vaccination-records-heading">{{ $pet->pet_name ?: 'Your pet' }}'s vaccination records</h2>
                        <p>Vaccination history and due dates entered by your veterinary team.</p>
                    </div>
                </div>

                <div class="vaccination-summary">
                    <div class="vaccination-summary-copy">
                        <strong>{{ $vaccinationSummary['coreUpToDate'] }} of {{ $vaccinationSummary['coreTotal'] }} core vaccines up to date</strong>
                        @if ($vaccinationSummary['nextDueRecord'])
                            <span>Next due: {{ $vaccineLabels[$vaccinationSummary['nextDueRecord']->vaccine_key] ?? $vaccinationSummary['nextDueRecord']->vaccine_key }} · {{ $vaccinationSummary['nextDueRecord']->next_due_date->format('M j, Y') }}</span>
                        @else
                            <span>No upcoming due date is recorded.</span>
                        @endif
                    </div>
                    @if ($vaccinationRequestKey)
                        <a class="btn" href="{{ route('owner.dashboard', ['pet_id' => $pet->id, 'service_type' => 'Vaccination', 'vaccine' => $vaccinationRequestKey]) }}#request-appointment">Request vaccination</a>
                    @endif
                </div>

                <div class="vaccination-list">
                    @foreach ($vaccinationRows as $vaccinationRow)
                        @php($record = $vaccinationRow['record'])
                        @php($vaccineKey = $vaccinationRow['vaccine_key'])
                        @php($status = $vaccinationRow['status'])
                        <article class="vaccination-item">
                            <div>
                                <div class="vaccination-item-heading">
                                    <strong>{{ $vaccineLabels[$vaccineKey] ?? $vaccineKey }}</strong>
                                    <span class="vaccination-category">{{ $vaccineCategories[$vaccineKey] ?? 'Non-core (Lifestyle)' }}</span>
                                </div>
                            </div>
                            <span class="vaccination-status {{ $status }}">
                                {{ ['up_to_date' => 'Up to date', 'due_soon' => 'Due soon', 'overdue' => 'Overdue', 'not_yet_given' => 'Not yet given'][$status] }}
                            </span>
                            <div class="vaccination-details">
                                <div class="vaccination-detail"><strong>Date given</strong>{{ $record?->date_given?->format('M j, Y') ?? 'Not recorded' }}</div>
                                <div class="vaccination-detail"><strong>Next due date</strong>{{ $record?->next_due_date?->format('M j, Y') ?? 'Not set' }}</div>
                                <div class="vaccination-detail"><strong>Veterinarian</strong>{{ $record?->veterinarian_name ?: 'Not recorded' }}</div>
                                <div class="vaccination-detail"><strong>Dose number</strong>{{ $record?->dose_number ?: 'Not recorded' }}</div>
                                @if ($record)
                                    <div class="vaccination-detail"><strong>Brand</strong>{{ $record->brand ?: 'Not recorded' }}</div>
                                    <div class="vaccination-detail"><strong>Batch / lot number</strong>{{ $record->batch_number ?: 'Not recorded' }}</div>
                                @endif
                            </div>
                            @if ($record?->notes)
                                <p class="vaccination-notes"><strong>Notes:</strong> {{ $record->notes }}</p>
                            @endif
                            @if ($status !== 'up_to_date')
                                <a class="vaccination-request-link" href="{{ route('owner.dashboard', ['pet_id' => $pet->id, 'service_type' => 'Vaccination', 'vaccine' => $vaccineKey]) }}#request-appointment">Request this vaccination</a>
                            @endif
                        </article>
                    @endforeach
                </div>

            </section>
        @endif

        <section class="add-pet-section" aria-labelledby="add-pet-heading">
            <div class="add-pet-copy">
                <div class="add-pet-icon" aria-hidden="true">＋</div>
                <div>
                    <h2 id="add-pet-heading">Add another pet</h2>
                    <p>Have another animal at home? Create a separate profile for their details, photo, and appointments.</p>
                </div>
            </div>
            <details class="add-pet-details">
                <summary><span aria-hidden="true">＋</span> Add additional pet</summary>
                <form class="add-pet-form" method="POST" action="{{ route('owner.pets.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="field">
                        <label for="new_pet_name">Pet name</label>
                        <input id="new_pet_name" name="pet_name" value="{{ old('pet_name') }}" maxlength="255" required>
                    </div>
                    <div class="field">
                        <label for="new_pet_type">Pet type</label>
                        <select id="new_pet_type" name="pet_type" required>
                            <option value="">Select type</option>
                            @foreach (['dog' => 'Dog', 'cat' => 'Cat', 'rabbit' => 'Rabbit', 'fish' => 'Fish', 'bird' => 'Bird', 'reptile' => 'Reptile', 'other' => 'Other'] as $type => $label)
                                <option value="{{ $type }}" {{ old('pet_type') === $type ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field" id="new-other-pet-field" @if (old('pet_type') !== 'other') hidden @endif>
                        <label for="new_pet_type_other">What kind of animal?</label>
                        <input id="new_pet_type_other" name="pet_type_other" value="{{ old('pet_type_other') }}" maxlength="255">
                    </div>
                    <div class="field">
                        <label for="new_pet_birthdate">Birthdate <span style="font-weight:400;">(optional)</span></label>
                        <input id="new_pet_birthdate" name="pet_birthdate" type="date" max="{{ now()->toDateString() }}" value="{{ old('pet_birthdate') }}">
                    </div>
                    <div class="field">
                        <label for="new_pet_photo">Photo <span style="font-weight:400;">(optional)</span></label>
                        <input id="new_pet_photo" name="pet_photo" type="file" accept="image/jpeg,image/png,image/webp">
                    </div>
                    <button class="btn" type="submit">Add pet</button>
                </form>
            </details>
        </section>
    </main>
    <script>
        const serviceType = document.getElementById('service_type');
        const vetField = document.getElementById('vet-field');
        const vetSelect = document.getElementById('vet_id');
        const groomerField = document.getElementById('groomer-field');
        const groomerSelect = document.getElementById('groomer_id');
        const vaccinesField = document.getElementById('vaccines-field');
        const groomingFields = document.getElementById('grooming-fields');

        function toggleStaffDropdown() {
            const isGrooming = serviceType.value === 'Grooming';
            vetField.hidden = isGrooming;
            vetSelect.disabled = isGrooming;
            groomerField.hidden = !isGrooming;
            groomerSelect.disabled = !isGrooming;
            groomingFields.hidden = !isGrooming;
            groomingFields.disabled = !isGrooming;
            if (vaccinesField) {
                const isVaccination = serviceType.value === 'Vaccination';
                vaccinesField.hidden = !isVaccination;
                vaccinesField.disabled = !isVaccination;
            }
        }

        serviceType.addEventListener('change', toggleStaffDropdown);
        toggleStaffDropdown();

        const newPetType = document.getElementById('new_pet_type');
        const otherPetField = document.getElementById('new-other-pet-field');
        const otherPetInput = document.getElementById('new_pet_type_other');

        function toggleNewOtherPetType() {
            if (!newPetType || !otherPetField || !otherPetInput) return;
            const isOther = newPetType.value === 'other';
            otherPetField.hidden = !isOther;
            otherPetInput.required = isOther;
            otherPetInput.disabled = !isOther;
        }

        if (newPetType) {
            newPetType.addEventListener('change', toggleNewOtherPetType);
            toggleNewOtherPetType();
        }

        const fvrcpCombo = document.getElementById('vaccine-cat_fvrcp');
        const fvrcpComponents = ['cat_rhinotracheitis', 'cat_calicivirus', 'cat_panleukopenia']
            .map((vaccine) => document.getElementById(`vaccine-${vaccine}`))
            .filter(Boolean);
        if (fvrcpCombo) {
            const allComponentsSelected = () => fvrcpComponents.length > 0
                && fvrcpComponents.every((input) => input.checked);

            const syncFvrcpCombo = () => {
                fvrcpCombo.checked = allComponentsSelected();
            };

            fvrcpCombo.addEventListener('change', () => {
                if (fvrcpCombo.checked) {
                    fvrcpComponents.forEach((input) => {
                        input.checked = true;
                    });
                } else if (allComponentsSelected()) {
                    fvrcpCombo.checked = true;
                }
            });

            fvrcpComponents.forEach((input) => {
                input.addEventListener('change', syncFvrcpCombo);
            });
            if (fvrcpCombo.checked) {
                fvrcpComponents.forEach((input) => {
                    input.checked = true;
                });
            }
            syncFvrcpCombo();
        }
    </script>
</body>
</html>
