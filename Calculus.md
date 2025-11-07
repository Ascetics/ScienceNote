**平面区域D是由 $x=0$, $y=\sqrt{3(1-x^2)}$, $y=\sqrt{3}x$ 围成的，求二重积分 $I= \iint \limits_D x^2 \mathrm{d}x \mathrm{d}y$。**

$I=\int_0^{\frac{\sqrt{2}}{2}}\mathrm{d}x\int_{\sqrt{3}x}^{\sqrt{3(1-x^2)}}x^2\mathrm{d}y=\sqrt{3}(\int_0^{\frac{\sqrt{2}}{2}}x^2\sqrt{1-x^2}\mathrm{d}x-\int_x^{\frac{\sqrt{2}}{2}}x^3\mathrm{d}x)=\sqrt{3}(I_1-I_2)$

$
\begin{aligned}
I_1
&\xlongequal{\quad x=\sin\theta\quad} \int_0^\frac{\pi}{4} \sin^2\theta \cos^2\theta  \mathrm{d}\theta \\
&= \frac{1}{4} \int_0^\frac{\pi}{4} \sin^2 2\theta \mathrm{d}\theta \\
&= \frac{1}{8} \int_0^\frac{\pi}{4} 1-\cos 4\theta \mathrm{d}\theta \\
&= \left.{\frac{1}{8}(\theta-\frac{1}{4}\sin 4\theta)} \right|_0^1 \\
&= \frac{\pi}{32}
\end{aligned}
$

$
\begin{aligned}
I_2=\left.{\frac{1}{4}x^4} \right|_0^{\frac{\sqrt{2}}{2}}=\frac{1}{16}
\end{aligned}
$

$
\begin{aligned}
I&=\sqrt{3}(I_1-I_2)\\
&=\sqrt{3}(\frac{\pi}{32}-\frac{1}{16})\\
&=\frac{\sqrt{3}(\pi-2)}{32}
\end{aligned}
$

