import { serve } from 'https://deno.land/std@0.177.0/http/server.ts'
import { createClient } from 'https://esm.sh/@supabase/supabase-js@2'


serve(async (req) => {
	try {
		return new Response(JSON.stringify({'message': 'Hello Players!'}), {
			headers: { 'Content-Type': 'application/json' },
			status: 200,
		})
	} catch (error) {
		return new Response(JSON.stringify({ error: error.message }), {
			headers: { 'Content-Type': 'application/json' },
			status: 400,
		})
	}
})
