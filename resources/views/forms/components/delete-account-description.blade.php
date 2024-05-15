<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
        <div class="text-left">
            <div class="mt-4 text-gray-600 text-sm w-1/3">
                Depois que sua conta for excluída, todos os seus recursos e dados serão permanentemente apagados.
                Antes de excluir sua conta, por favor, faça o download de quaisquer dados ou informações que deseje manter.
            </div>
        </div>
    </div>
</x-dynamic-component>
