<!DOCTYPE html>
<html lang="en">
@include('welcome.layout.head')
@include('welcome.layout.nav')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Fimo Chic</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <div class="wrapper">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2>Register</h2>

            <div class="input-field">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                <label for="name">Enter your name</label>
                @error('name')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required>
                <label for="nom">Enter your last name</label>
                @error('nom')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                <label for="email">Enter your email</label>
                @error('email')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="password" type="password" name="password" required>
                <label for="password">Enter your password</label>
                @error('password')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="age" type="number" name="age" value="{{ old('age') }}" min="18" max="100" required>
                <label for="age">Choose your age</label>
                @error('age')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="numeroTel" type="tel" name="numeroTel" value="{{ old('numeroTel') }}" required>
                <label for="numeroTel">Enter your phone number</label>
                @error('numeroTel')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <label for="gender">Select your gender:</label><br>
                <br>
                <br><br>
                <div class="radio-group">
                    <input type="radio" id="male" name="gender" value="male" class="radio-custom" required>
                    <label for="male">Male</label>
                    <input type="radio" id="female" name="gender" value="female" class="radio-custom">
                    <label for="female">Female</label>
                   
                </div>
                @error('gender')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-field">
                <input id="adresse" type="text" name="adresse" value="{{ old('adresse') }}" required>
                <label for="adresse">Enter your address</label>
                @error('adresse')
                <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Register</button>

            <div class="register">
                <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
            </div>
        </form>
    </div>
</body>
<style>
    /* Importation de la police Google Fonts */
    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");

    /* Réinitialisation des styles et utilisation de la police principale */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Open Sans", sans-serif;
    }

    /* Style général pour le corps de la page */
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        width: 100%;
        padding: 0 10px;
        /* Utilisation d'une image de fond avec un filtre et positionnement */
        background: url("{{ asset('img/ghof.jpg') }}") center/cover fixed, #000; /* Image de fond fixe */
        background-position: center;
        background-size: cover;
        position: relative;
    }

    /* Style pour le conteneur principal */
    .wrapper {
        margin-top: 150px;
        margin-bottom: 150px;

        width: 600px;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.5);
        backdrop-filter: blur(9px);
        -webkit-backdrop-filter: blur(9px);
        /* Positionnement absolu pour le fond flou */
        position: relative;
        z-index: 1;
        margin-left: 200px;
    }

    /* Style pour le formulaire */
    form {
        display: flex;
        flex-direction: column;
    }

    /* Style pour les titres */
    h2 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: #fff;
    }

    /* Style pour les champs de saisie */
    .input-field {
        position: relative;
        margin: 15px 0;
    }

    .input-field label {
        position: absolute;
        top: 50%;
        left: 0;
        transform: translateY(-50%);
        color: #fff;
        font-size: 16px;
        pointer-events: none;
        transition: 0.15s ease;
    }

    .input-field input {
        width: 50%;
        background: transparent;
        border: none;
        outline: none;
        font-size: 16px;
        color: #fff;
    }

    .input-field input:focus~label,
    .input-field input:valid~label {
        font-size: 0.8rem;
        top: 10px;
        transform: translateY(-120%);
    }

  /* Style pour le groupe de boutons radio */
.radio-group {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 10px;
    color: #fff;
}

/* Style pour les boutons radio personnalisés */
.radio-group input[type="radio"] {
    /*opacity: 0;  Masque le bouton radio par défaut 
    width: 0;
    height: 0;
    position: absolute;*/
}

.radio-group label {
    position: relative;
    cursor: pointer;
    padding-left: 25px;
    font-size: 16px;
    line-height: 1.5;
}

.radio-group label:before {
    content: '';
    position: absolute;
    left: 0;
    top: 2px;
    width: 0px;
    height: 0px;
    border: 2px  ;
    border-radius: 50%;
    background-color: transparent;
}

.radio-group input[type="radio"]:checked + label:before {
    background-color: #fff;
}

.radio-group input[type="radio"]:focus + label:before {
    /* Ajoutez un style pour la mise au point si nécessaire */
}

.radio-group input[type="radio"]:hover + label:before {
    /* Ajoutez un style pour le survol si nécessaire */
}

    /* Style pour le lien de mot de passe oublié */
    .forget {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 25px 0 35px 0;
        color: #fff;
    }

    /* Style pour le bouton de soumission */
    button {
        background: #fff;
        color: #000;
        font-weight: 600;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        border-radius: 3px;
        font-size: 16px;
        border: 2px solid transparent;
        transition: 0.3s ease;
    }

    button:hover {
        color: #fff;
        border-color: #fff;
        background: rgba(255, 255, 255, 0.15);
    }

    /* Style pour le lien d'enregistrement */
    .register {
        text-align: center;
        margin-top: 30px;
        color: #fff;
    }

    /* Style pour les liens */
    .wrapper a {
        color: #efefef;
        text-decoration: none;
    }

    .wrapper a:hover {
        text-decoration: underline;
    }

    /* Style pour l'élément de rappel */
    #remember {
        accent-color: #fff;
    }

    /* Style pour les étiquettes dans les champs de saisie */
    .forget label {
        display: flex;
        align-items: center;
    }

    .forget label p {
        margin-left: 8px;
    }
</style>

</html>
``
