<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pet Place — Home</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet"/>
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
:root{
  --bg:#e8e4f8;
  --hero:#d8d2f5;
  --white:#ffffff;
  --accent:#6c4fc7;
  --accent2:#8b6fd4;
  --text:#1a1523;
  --text2:#5a5470;
  --text3:#9490a8;
  --paw:rgba(108,79,199,0.08);
}

html,body{height:100%;font-family:'DM Sans',sans-serif;color:var(--text);}

/* ── HEADER ── */
header{
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:20px 48px;
  background:transparent;
  position:absolute;
  top:0;left:0;right:0;
  z-index:10;
}

.logo{
  font-size:22px;
  font-weight:700;
  color:var(--text);
  letter-spacing:-0.02em;
}
.logo span{color:var(--accent);}

.header-right{
  display:flex;align-items:center;gap:12px;
}

.phone-btn{
  display:flex;align-items:center;gap:7px;
  background:var(--white);
  border:1px solid rgba(108,79,199,0.15);
  border-radius:40px;
  padding:9px 18px;
  font-size:13px;
  font-weight:500;
  color:var(--text2);
  text-decoration:none;
  transition:all 0.2s;
  box-shadow:0 2px 8px rgba(0,0,0,0.06);
}
.phone-btn:hover{
  background:var(--accent);
  color:#fff;
  border-color:var(--accent);
}
.phone-btn span{font-size:14px;}

/* ── HERO ── */
.hero{
  min-height:100vh;
  background:var(--hero);
  display:flex;
  align-items:center;
  position:relative;
  overflow:hidden;
  padding:100px 48px 60px;
}

/* Paw print background decorations */
.hero::before{
  content:'🐾';
  position:absolute;
  font-size:200px;
  opacity:0.04;
  top:10%;left:5%;
  transform:rotate(-20deg);
  pointer-events:none;
}
.hero::after{
  content:'🐾';
  position:absolute;
  font-size:150px;
  opacity:0.04;
  bottom:10%;right:30%;
  transform:rotate(15deg);
  pointer-events:none;
}

/* Dog image */
.hero-image{
  position:absolute;
  left:0;bottom:0;
  height:90%;
  max-height:600px;
  object-fit:contain;
  pointer-events:none;
  filter:drop-shadow(0 8px 32px rgba(108,79,199,0.15));
}

/* Content */
.hero-content{
  margin-left:38%;
  max-width:520px;
  position:relative;
  z-index:2;
  animation:fadeRight 0.6s ease;
}

@keyframes fadeRight{
  from{opacity:0;transform:translateX(20px);}
  to{opacity:1;transform:none;}
}

.welcome-badge{
  display:inline-flex;
  align-items:center;
  gap:6px;
  background:rgba(108,79,199,0.1);
  border:1px solid rgba(108,79,199,0.2);
  border-radius:40px;
  padding:6px 14px;
  font-size:12px;
  font-weight:600;
  color:var(--accent);
  letter-spacing:0.04em;
  margin-bottom:20px;
}

.hero-title{
  font-size:clamp(48px, 6vw, 72px);
  font-weight:800;
  line-height:1.0;
  letter-spacing:-0.03em;
  color:var(--text);
  margin-bottom:10px;
}

.hero-subtitle{
  font-size:16px;
  font-weight:600;
  color:var(--text2);
  margin-bottom:10px;
}

.hero-desc{
  font-size:14px;
  color:var(--text3);
  line-height:1.7;
  margin-bottom:32px;
  max-width:380px;
}

/* CTA Button */
.cta-wrap{
  display:flex;
  align-items:center;
  gap:14px;
}

.cta-btn{
  display:inline-flex;
  align-items:center;
  gap:10px;
  background:var(--accent);
  color:#fff;
  border:none;
  border-radius:40px;
  padding:14px 28px;
  font-family:'DM Sans',sans-serif;
  font-size:15px;
  font-weight:600;
  cursor:pointer;
  text-decoration:none;
  transition:all 0.2s;
  box-shadow:0 4px 20px rgba(108,79,199,0.3);
}
.cta-btn:hover{
  background:var(--accent2);
  transform:translateY(-2px);
  box-shadow:0 8px 28px rgba(108,79,199,0.4);
}
.cta-btn:active{transform:scale(0.97);}

.cta-arrow{
  width:32px;height:32px;
  background:rgba(255,255,255,0.2);
  border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:14px;
  transition:transform 0.2s;
}
.cta-btn:hover .cta-arrow{transform:translateX(3px);}

/* Stats */
.stats{
  display:flex;
  gap:28px;
  margin-top:36px;
}
.stat{
  display:flex;flex-direction:column;gap:2px;
}
.stat-num{
  font-size:22px;font-weight:700;
  color:var(--accent);letter-spacing:-0.02em;
}
.stat-label{font-size:12px;color:var(--text3);}

/* Bottom contact bar */
.bottom-bar{
  position:absolute;
  bottom:28px;left:48px;
  display:flex;align-items:center;gap:8px;
  font-size:13px;color:var(--text2);
  z-index:2;
}
.bottom-bar .dot{
  width:28px;height:28px;
  background:rgba(108,79,199,0.1);
  border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:12px;
}

/* Responsive */
@media(max-width:768px){
  header{padding:16px 20px;}
  .hero{padding:80px 20px 100px;}
  .hero-image{height:50%;opacity:0.3;}
  .hero-content{margin-left:0;}
  .stats{gap:16px;}
}
</style>
</head>
<body>

<!-- HEADER -->
<header>
  <div class="logo">Pet <span>Care.</span></div>
  <div class="header-right">
    <a href="tel:800-234-567" class="phone-btn">
      <span>📞</span> 800-234-567
    </a>
  </div>
</header>

<!-- HERO -->
<section class="hero">

  <!-- Dog image — replace src with your actual dog image -->
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Dog_Breeds.jpg/800px-Dog_Breeds.jpg"
       alt="Pet" class="hero-image" onerror="this.style.display='none'"/>

  <div class="hero-content">
    <div class="welcome-badge">🐾 Welcome</div>

    <h1 class="hero-title">Pet<br/>Care</h1>

    <p class="hero-subtitle">We love pets like you do :)</p>
    <p class="hero-desc">
      One modern portal for pet owners and vets: book visits,
      track vaccines and keep every record in one place.
    </p>

    <div class="cta-wrap">
      <a href="{{ route('choose') }}" class="cta-btn">
        Start Here!
        <span class="cta-arrow">→</span>
      </a>
    </div>

    <div class="stats">
      <div class="stat">
        <span class="stat-num">500+</span>
        <span class="stat-label">Pet Owners</span>
      </div>
      <div class="stat">
        <span class="stat-num">50+</span>
        <span class="stat-label">Veterinarians</span>
      </div>
      <div class="stat">
        <span class="stat-num">1,200+</span>
        <span class="stat-label">Records Tracked</span>
      </div>
    </div>
  </div>

  <!-- Bottom contact -->
  <div class="bottom-bar">
    <div class="dot">👤</div>
    Call Us Today &nbsp;<strong>800-236-789</strong>
  </div>

</section>

</body>
</html>