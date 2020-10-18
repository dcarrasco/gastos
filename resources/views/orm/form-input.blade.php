<x-form-input
    :type="$type"
    :name="$name"
    :value="$value"
    class="w-full"
    :id="$id"
    :maxlength="$maxlength ?? ''"
    :options="$options ?? []"
    :placeholder="$placeholder ?? ''"
    :multiple="$multiple ?? ''"
    :size="$size ?? 0"
    :cols="$cols ?? 0"
    :rows="$rows ?? 0"
/>
