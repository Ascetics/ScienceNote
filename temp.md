**求 $\int x^2(3-2\cos^2 x) \mathrm{d}x$。**

思路：多项式和正余弦乘积，求导后，多项式降幂，正余弦交替出现。

$\begin{aligned}
\int x^2(3-2\cos^2 x) \mathrm{d}x &\xlongequal{\quad 1+\cos 2x = 2\cos^2x \quad} \int x^2(2-\cos 2x) \mathrm{d}x \\
&= \int 2x^2 \mathrm{d}x - \int x^2\cos 2x \mathrm{d}x
\end{aligned}$

$\begin{cases}
(x^2\sin 2x)' &= 2x^2\cos 2x &+ &2x\sin 2x &  &         &\text{①} \\
(x\cos 2x)'   &=             &- &2x\sin 2x &+ &\cos 2x  &\text{②} \\
(\sin 2x)'    &=             &  &          &  &2\cos 2x &\text{③}
\end{cases}$

构造合适的系数，可以只保留我们需要的项 $\frac{1}{2}①+\frac{1}{2}②-\frac{1}{4}③=x^2\cos 2x$

原式=$\frac{2}{3}x^3 - \frac{1}{2}x^2\sin 2x - \frac{1}{2}x\cos 2x - \frac{1}{4}\sin 2x + C$

---

**求 $\int \frac{1}{x+x^3} \mathrm{d}x$、$\int \frac{4}{x+x^4} \mathrm{d}x$。**

思路：大方向是构造 $\ln{f(x)}$， $f(x)$ 是分母，其导数冗余性再消除。

$\begin{aligned}
f(x)  &= \ln(x+x^B) \\
f'(x) &= \frac{1+Bx^{B-1}}{x+x^B}
\end{aligned}$

$\begin{aligned}
\int \frac{A}{x+x^B} \mathrm{d}x 
&= A(\int \frac{1+Bx^{B-1}}{x+x^B} \mathrm{d}x - \int \frac{Bx^{B-1}}{x+x^B} \mathrm{d}x) \\
&= A(\int \frac{1}{x+x^B} \mathrm{d}(x+x^B) - \frac{B}{B-1} \int \frac{1}{1+x^{B-1}} \mathrm{d}(1+x^{B-1})) \\ 
&= A(\ln|x+x^B| + \frac{B}{B-1}\ln|1+x^{B-1}|) \\
&= \frac{A}{B-1}\ln|\frac{x^B}{x+x^B}| + C
\end{aligned}$

所以

$\begin{aligned}
\int \frac{1}{x+x^3} \mathrm{d}x &= \frac{1}{2}\ln|\frac{x^3}{x+x^3}| + C \\
\int \frac{4}{x+x^4} \mathrm{d}x &= \frac{4}{3}\ln|\frac{x^4}{x+x^4}| + C
\end{aligned}$

---
