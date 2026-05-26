<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>CredyFácil</title>
	<link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/tabler-vendors.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
	<link rel="icon" href="{{ asset('assets/images/xinergia-icon.svg') }}">
	<style>
		:root {
			--login-blue: #2f73ca;
			--login-link: #2563d8;
			--login-text: #061631;
			--login-muted: #657188;
			--login-border: #d9dee8;
		}

		html,
		body {
			min-height: 100%;
		}

		body {
			margin: 0;
			color: var(--login-text);
			background: #ffffff;
			font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
		}

		.login-page {
			display: grid;
			grid-template-columns: minmax(360px, 34%) 1fr;
			min-height: 100vh;
			background: #ffffff;
		}

		.login-panel {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 42px 48px;
		}

		.login-form-wrap {
			width: min(100%, 520px);
		}

		.login-xinergia {
			display: block;
			width: min(310px, 80%);
			height: auto;
			margin: 0 auto 32px;
		}

		.login-title {
			margin: 0 0 24px;
			font-size: 21px;
			font-weight: 700;
			line-height: 1.25;
			text-align: center;
		}

		.login-form .form-label {
			margin-bottom: 10px;
			color: #07162f;
			font-size: 19px;
			font-weight: 500;
		}

		.login-form .form-control {
			height: 46px;
			border: 1px solid var(--login-border);
			border-radius: 4px;
			color: #101828;
			font-size: 18px;
			box-shadow: none;
		}

		.login-form .form-control::placeholder {
			color: #9aa4b5;
		}

		.login-form .form-control:focus {
			border-color: #9dbcf0;
			box-shadow: 0 0 0 3px rgba(47, 115, 202, 0.12);
		}

		.password-label {
			display: flex;
			align-items: center;
			justify-content: space-between;
			gap: 16px;
		}

		.password-label a,
		.login-footer a {
			color: var(--login-link);
			text-decoration: none;
		}

		.password-label a:hover,
		.login-footer a:hover {
			text-decoration: underline;
		}

		.forgot-link {
			font-size: 18px;
			font-weight: 400;
			white-space: nowrap;
		}

		.login-button {
			height: 44px;
			margin-top: 22px;
			border: 0;
			border-radius: 4px;
			background: var(--login-blue);
			font-size: 18px;
			font-weight: 700;
		}

		.login-button:hover,
		.login-button:focus {
			background: #2767ba;
		}

		.login-footer {
			margin-top: 20px;
			color: var(--login-muted);
			font-size: 18px;
			text-align: center;
		}

		.brand-panel {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 48px 64px;
			background: #ffffff;
		}

		.credy-logo {
			width: min(76vw, 1120px);
			max-height: 50vh;
			object-fit: contain;
		}

		@media (max-width: 991.98px) {
			.login-page {
				grid-template-columns: 1fr;
			}

			.login-panel {
				min-height: auto;
				padding: 44px 22px 28px;
			}

			.brand-panel {
				padding: 12px 22px 42px;
			}

			.credy-logo {
				width: min(92vw, 620px);
				max-height: 260px;
			}
		}

		@media (max-width: 575.98px) {
			.login-panel {
				align-items: flex-start;
			}

			.login-xinergia {
				width: min(260px, 82%);
				margin-bottom: 26px;
			}

			.login-title {
				font-size: 20px;
			}

			.password-label {
				align-items: flex-start;
				flex-direction: column;
				gap: 4px;
			}

			.login-form .form-label,
			.forgot-link,
			.login-footer {
				font-size: 16px;
			}
		}
	</style>
</head>

<body>
	<main class="login-page">
		<section class="login-panel">
			<div class="login-form-wrap">
				<img src="{{ asset('assets/images/xinergia.png') }}" class="login-xinergia" alt="Xinergia">

				<h1 class="login-title">Ingresa con tu cuenta</h1>

				<form id="loginForm" class="login-form" action="{{ route('auth.check') }}" method="POST" autocomplete="off">
					@csrf
					<div class="mb-3">
						<label class="form-label" for="user">Usuario</label>
						<input id="user" type="text" name="user" class="form-control @error('user') is-invalid @enderror"
							placeholder="Tu usuario" value="{{ old('user') }}" autocomplete="off">
						@error('user')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>

					<div class="mb-3">
						<label class="form-label password-label" for="password">
							<span>Contraseña</span>
							<a class="forgot-link" href="#">Olvidé mi contraseña</a>
						</label>
						<input id="password" type="password" name="password"
							class="form-control @error('password') is-invalid @enderror" placeholder="Tu contraseña"
							autocomplete="off">
						@error('password')
							<div class="invalid-feedback">{{ $message }}</div>
						@enderror
					</div>

					<button id="loginBtn" type="submit" class="btn btn-primary w-100 login-button">Iniciar sesión</button>
				</form>

				<div class="login-footer">
					Elaborado por Xinergia de <a href="#">Corporacion Xpande</a>
				</div>
			</div>
		</section>

		<section class="brand-panel" aria-label="CredyFácil Soluciones Financieras">
			<img src="{{ asset('assets/images/logo.png') }}" class="credy-logo" alt="CredyFácil Soluciones Financieras">
		</section>
	</main>

	<script src="{{ asset('assets/js/tabler.min.js') }}"></script>
	<script src="{{ asset('assets/js/theme.min.js') }}"></script>
	<script src="{{ asset('assets/js/tom-select.base.min.js') }}"></script>
	<script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
	<script>
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$(document).ready(function() {
			$('#loginForm').submit(function() {
				$('#loginBtn').prop('disabled', true).text('Iniciando sesión...');
			});
		});
	</script>
</body>

</html>
