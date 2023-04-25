import { serve } from 'https://deno.land/std@0.177.0/http/server.ts'
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2'


const corsHeaders = {
	'Access-Control-Allow-Origin': '*',
	'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey',
}

serve(async (req) => {
	if (req.method === 'OPTIONS') {
		return new Response('ok', { headers: corsHeaders })
	}

	try {
		// Create a Supabase client with the Auth context of the logged in user.
		const supabaseClient = createClient(
			// Supabase API URL - env var exported by default.
			Deno.env.get('SUPABASE_URL') ?? '',
			// Supabase API ANON KEY - env var exported by default.
			Deno.env.get('SUPABASE_ANON_KEY') ?? '',
			// Create client with Auth context of the user that called the function.
			// This way your row-level-security (RLS) policies are applied.
			{ global: { headers: { Authorization: req.headers.get('Authorization')! } } }
		)

		const { data, error } = await supabaseClient.storage
			.from('functions-test').download('sample.txt')

		if (error) throw error

		const contents = await data.text()

		return new Response(contents, {
			headers: { ...corsHeaders, 'Content-Type': 'text/plain' },
			status: 200,
		})
	} catch (error) {
		return new Response(JSON.stringify({ error: error.message }), {
			headers: { ...corsHeaders, 'Content-Type': 'application/json' },
			status: 400,
		})
	}
})
