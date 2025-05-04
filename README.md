# 🍽️ Systém rezervace stolů pro restauraci

Projekt představuje systém rezervace stolů v restauraci, postavený na Laravelu s použitím Filamentu.  
Uživatelé mohou vytvářet rezervace, zobrazovat své rezervace a administrátor může spravovat uživatele, stoly a rezervace. [Demo](https://skyblue-dogfish-961725.hostingersite.com)

---

## 🚀 Instalace projektu

1. Naklonujte repozitář:
```bash
git clone https://github.com/romanlazko/snadnee.git
```

2. Instalace závislostí:
```bash
cd snadnee
composer install
```

3. Vytvořte kopii `.env.example` jako `.env`:
```bash
cp .env.example .env
```

4. Generujte aplikaci:
```bash
php artisan key:generate
```

5. Vytvořte databázi:
```bash
php artisan migrate
```

6. Spusťte frontu pro odesílání e-mailových notifikací:
```bash
php artisan queue:work
```

7. Přihlášení do administrace:
- **Email:** admin@admin.com  
- **Heslo:** password

---

## 💡 Proč Filament?

Ano, Filament bývá kritizován za výkon na velkých datech, ale pro malý projekt je to ideální volba.  
Umožňuje:
- rychle vytvořit administrační rozhraní bez potřeby API,
- použít hotové komponenty a neplýtvat časem na jejich ruční vývoj,
- soustředit se na obchodní logiku místo infrastruktury.

Pro byznys je rychlost vývoje klíčová — a zde Filament skvěle obstojí.

---

## ❓ Otázky, které bych položil u reálného projektu

- **Jak má fungovat rezervační systém?**  
  Například, zda je nutná hodinová rezervace a kontrola kolizí — tedy pokud si někdo zarezervuje od 12:00 do 14:00, tento časový úsek by měl být blokován.

- **Je potřeba předem vytvářet volné sloty?**  
  Systém by mohl fungovat s automatickým nebo ručním vytvářením slotů se statusem „volné“, „rezervované“, „zrušené“. Po zrušení rezervace by slot zůstal v databázi, ale byl by opět volný.

- **Je potřeba omezit počet rezervací na uživatele?**

- **Je nutná registrace uživatele?**  
  V reálném projektu bych navrhoval rezervaci pouze přes email a odeslání „magického odkazu“, přes který by šlo rezervaci spravovat. Tento přístup zjednodušuje proces a zvyšuje počet rezervací.

- **Chce klient využít sběr kontaktů?**  
  Například k newsletterům nebo promoakcím přes email nebo sociální sítě.

---

## ⚙️ Implementované řešení

- Systém rezervací s registrací a uživatelským profilem.
- Laravel + Filament + MySQL.
- Uživatelský účet pro správu rezervací a úpravu profilu.
- Admin rozhraní pro správu uživatelů, stolů a rezervací.
- Kontrola obsazenosti stolů: pokud je stůl obsazený na vybraný den, nezobrazuje se.
- E-mailové notifikace při vytvoření nebo zrušení rezervace.
- Pokrytí projektu testy.
- Role a oprávnění pomocí Spatie Role-Permission.
- Admin a základní role jsou vytvořeny v migracích.

---

## 🔧 Specifika aplikace a použité přístupy

- **Jednoduchý rezervační model:**  
  Uživatel vybírá datum a čas; pokud je stůl obsazený, zmizí ze seznamu. Není potřeba složitých pravidel (pokud si to klient výslovně nepřeje).

- **Indexy v databázi:**  
  Aby se zabránilo duplicitám rezervací na stejný den a stůl.

- **Emailové notifikace:**  
  Používá Laravel Notifications; Event-Listenery jsem nepoužil, protože akcí není mnoho.

- **Mazání závislostí:**  
  Při smazání stolu nebo uživatele se smažou i rezervace (přes `booted()` metodu v modelu, ne pomocí Observerů).

- **Role:**  
  Spatie Role-Permission; klíčová data (např. admin účet a role) jsou vytvořena v migracích.

- **UI:**  
  Filtrace podle data a seskupení podle stolů, jednoduchý design (lze rozšířit např. o Kanban nebo FullCalendar Resource).

---

## 🧪 Testování

- Laravel DebugBar pro sledování dotazů.
- Projekt pokrytý testy.  
  **Důležité:** Testy jsem začal studovat teprve nedávno, proto bych ocenil jakoukoliv zpětnou vazbu.  
  Pokusil jsem se pokrýt hlavní mechanismy, stránky a formuláře.