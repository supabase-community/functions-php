```
npm install
cd tests/fixtures
// Note: you will need a personal access toke to login
npx supabase login
// Use the project ref of an test project
npx supabase link --project-ref=
// Upload the test functions
npx supabase functions deploy cors --project-ref=
npx supabase functions deploy hello-world --project-ref=
```
