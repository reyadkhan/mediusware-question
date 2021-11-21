<select name="variant" id="" class="form-control">
    <option value="" selected>Select...</option>
    @foreach($variants as $variant)
        <optgroup label="{{ $variant->title }}">
            @foreach($variant->productVariants as $productVariant)
                <option value="{{ $productVariant->variant }}" {{ request()->input('variant') == $productVariant->variant ? 'selected' : '' }}>{{ $productVariant->variant }}</option>
            @endforeach
        </optgroup>
    @endforeach
</select>
